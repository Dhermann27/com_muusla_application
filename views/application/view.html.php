<?php
/**
 * @package		muusla_application
 * @license		GNU/GPL, see LICENSE.php
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the muusla_application campers Component
 *
 * @package		muusla_application
 */
class muusla_applicationViewapplication extends JViewLegacy
{

   function display($tpl = null) {
      $model = $this->getModel();
      $user = JFactory::getUser();
      $editcamper = $this->getSafe(JRequest::getVar("editcamper"));
      $admin = $editcamper && (in_array("8", $user->groups) || in_array("10", $user->groups));

      $sendmail = 0;
      $calls[][] = array();
      foreach(JRequest::get() as $key=>$value) {
         if(preg_match('/^(\w+)-(\w+)-(\d+)$/', $key, $objects)) {
            $table = $this->getSafe($objects[1]);
            $column = $this->getSafe($objects[2]);
            $id = $this->getSafe($objects[3]);
            if($calls[$table][$id] == null) {
               $obj = new stdClass;
               $obj->created_by = $user->username;
               $calls[$table][$id] = $obj;
            }
            $calls[$table][$id]->$column = $this->getSafe($value);
         }
      }
      $valideditor = false;
      if(isset($calls["camper"])) {
         foreach($calls["camper"] as $oldcamperid => $camper) {
            $valideditor = $valideditor || strcasecmp($camper->email, $user->email) == 0;
         }
      }
      if($admin || $valideditor || !isset($calls["family"])) {
         if(isset($calls["family"])) {
            foreach($calls["family"] as $id => $family) {
               $familyid = $model->upsertFamily($family);
               if($editcamper == "1") {
                  $editcamper = "$familyid";
               }
            }
         }
         if(isset($calls["camper"])) {
            $emails = array();
            foreach($calls["camper"] as $oldcamperid => $camper) {
               if($camper->firstname != "" && $camper->lastname != "") {
                  $camper->familyid = $familyid;
                  $days = $camper->attending;
                  $newcamperid = $model->upsertCamper($camper);
                  if(count($calls["phonenumber"]) > 0) {
                     foreach($calls["phonenumber"] as $phonenumber) {
                        if($phonenumber->camperid == $oldcamperid) {
                           $phonenumber->camperid = $newcamperid;
                        }
                     }
                  }
                  if(count($calls["charge"])) {
                     foreach($calls["charge"] as $charge) {
                        $charge->camperid = $newcamperid;
                     }
                  }
                  if($days != "-1") {
                     if($camper->email != "") {
                        array_push($emails, $camper->email);
                     }
                     $yearattendingid = $model->upsertYearattending($newcamperid, $days);
                     $model->deleteRoommatepreferences($yearattendingid, $camper->attending);
                     if(count($calls["roommatepreference"][$oldcamperid]->names) > 0) {
                        foreach(explode(",", $calls["roommatepreference"][$oldcamperid]->names) as $choicenbr => $name) {
                           $model->insertRoommatepreferences($yearattendingid, $choicenbr+1, $this->getSafe(urldecode($name)));
                        }
                     }
                     $sendmail = 1;
                  } else {
                     $model->deleteYearattending($newcamperid);
                  }
               }
            }
            if(count($calls["charge"]) > 0) {
               foreach($calls["charge"] as $charge) {
                  if($charge->amount != 0) {
                     $model->upsertCharge($charge, $newcamperid);
                  }
               }
            }
            $model->callTrigger($familyid);
            if($this->getSafe(JRequest::getVar('paypal-amount')) != 0) {
               $msg = floatval($this->getSafe(JRequest::getVar('paypal-amount')));
               $this->assignRef("redirectAmt", $msg);
            } else {
               $msg = true;
               $this->assignRef("msg", $msg);

            }
         }
         if(isset($calls["phonenumber"])) {
            $phonenbrs = array();
            foreach($calls["phonenumber"] as $key => $phonenumber) {
               if($key>= 1000 && $phonenumber->phonenbr != "") {
                  array_push($phonenbrs, $key);
               }
            }
            $model->deleteOldPhonenumbers(implode(",", array_keys($calls["camper"])), implode(",", $phonenbrs));
            foreach($calls["phonenumber"] as $phonenumber) {
               if($phonenumber->phonenbr != "") {
                  $model->upsertPhonenumber($phonenumber);
               }
            }
         }

         if($sendmail == 1 && count($emails) > 0) {
            $mailer = JFactory::getMailer();
            $config = JFactory::getConfig();
            $mailer->setSender(array($config->get( 'config.mailfrom' ), $config->get( 'config.fromname' ) ));
            $mailer->addRecipient($emails);
            $mailer->setSubject("MUUSA Online Registration");
            $mailer->setBody("Dear camper,\n\nThank you for registering at muusa.org. We look forward to seeing you this summer.\n\nYou can find the latest information about your registration in your confirmation letter, always available online.\n\nhttp://muusa.org/index.php/component/muusla_tools/?view=letters&format=pdf&tmpl=component&Itemid=320\n(You may be required to login first.)\n\nSee you \"next week\",\n\nDan Hermann\nMUUSA Webmaster");
            $send = $mailer->Send();
            $model->refresh();
         }
      } else {
         echo "<h2>Invalid Permissions to Update</h2>\n";
      }

      // DATA SAVED, GET NEW DATA

      $sumdays = 0;
      $year = $model->getYear();
      if($admin && preg_match('/^\d+$/', $editcamper)) {
         $this->assignRef('editcamper', $editcamper);
         $family = $model->getFamily("f.id=" . $editcamper);
      } else {
         $family = $model->getFamily("c.email='" . $user->email . "'");
      }
      $this->assignRef('family', $family);
      if($family) {
         $campers = $model->getCampers($family->id);
         foreach($campers as $camper) {
            $camper->phonenbrs = $model->getPhonenumbers($camper->id);
            if($camper->yearattendingid) {
               $camper->roommates = $model->getRoommatepreferences($camper->yearattendingid);
            }
            $sumdays += $camper->days;
         }
         $charges = array();
         foreach($model->getCharges($family->id, $admin && preg_match('/^\d+$/', $editcamper) ? "byyear" : "thisyear") as $charge) {
            if($charges[$charge->year] == null) {
               $charges[$charge->year] = array($charge);
            } else {
               array_push($charges[$charge->year], $charge);
            }
         }
         $this->assignRef('charges', $charges);
      }
      $this->assignRef('campers', $campers);
      $this->assignRef('states', $model->getStates());
      $this->assignRef('foodoptions', $model->getFoodoptions());
      $this->assignRef('churches', $model->getChurches());
      $this->assignRef('phonetypes', $model->getPhonetypes());
      $this->assignRef('programs', $model->getPrograms());
      $this->assignRef('year', $year);
      if($admin) {
         $this->assignRef('chargetypes', $model->getChargetypes());
      }
      $this->assignRef('sumdays', $sumdays);


      // UGH THIS SUCKS
      if($admin && preg_match('/^\d+$/', $editcamper)) {
         $regcampers = $model->getRegisteredCampersByFamily("familyid=" . $editcamper);
      } else {
         $regcampers = $model->getRegisteredCampersByCamper("c.email='" . $user->email . "'");
      }
      if(JRequest::getVar('deleteMe') == "1") {
         $msg = true;
         $this->assignRef("msg", $msg);
         foreach($regcampers as $camper) {
            $model->deleteAttendees($camper->yearattendingid);
            $model->deleteOldVolunteers($camper->yearattendingid);
         }
      }
      if(count($calls["yearattending__workshop"]) > 0) {
         foreach($calls["yearattending__workshop"] as $dummy => $attendee) {
            $model->insertAttendee($attendee);
         }
      }
      if(count($calls["yearattending__volunteer"]) > 0) {
         foreach($calls["yearattending__volunteer"] as $dummy => $volunteer) {
            $model->insertVolunteer($volunteer);
         }
      }
      foreach($regcampers as $camper) {
         $camper->volunteers = $model->getVolunteers($camper->id);
         $camper->attendees = $model->getAttendees($camper->yearattendingid);
      }
      $this->assignRef('regcampers', $regcampers);
      $this->assignRef('volunteerpositions', $model->getVolunteerPositions());
      $times = $model->getTimes();
      foreach($model->getWorkshops() as $workshop) {
         if($workshop["days"] == "MTuWThF") {
            $workshop["days"] = "5 days";
         }
         $times[$workshop["timeslotid"]]["shops"][$workshop["id"]] = $workshop;
      }
      $this->assignRef('times', $times);

      parent::display($tpl);
   }

   function getSafe($obj)
   {
      return htmlspecialchars(trim($obj), ENT_QUOTES);
   }

}
?>
