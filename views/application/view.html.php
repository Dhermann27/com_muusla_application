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
class muusla_applicationViewapplication extends JView
{

   function display($tpl = null) {
      $model =& $this->getModel();
      $user =& JFactory::getUser();
      $editcamper = $this->getSafe(JRequest::getVar("editcamper"));
      $admin = $editcamper && (in_array("8", $user->groups) || in_array("10", $user->groups));
      $calls[][] = array();
      foreach(JRequest::get() as $key=>$value) {
         if(preg_match('/^(\w+)-(\w+)-(\d+)$/', $key, $objects)) {
            $table = $this->getSafe($objects[1]);
            $column = $this->getSafe($objects[2]);
            $id = $this->getSafe($objects[3]);
            if($calls[$table][$id] == null) {
               $obj = new stdClass;
               if($id < 1000) {
                  $obj->created_by = $user->username;
                  $obj->created_at = date("Y-m-d H:i:s");
               } else {
                  $obj->modified_by = $user->username;
                  $obj->modified_at = date("Y-m-d H:i:s");
               }
               $calls[$table][$id] = $obj;
            }
            $calls[$table][$id]->$column = $this->getSafe($value);
         }
      }
      $valideditor = false;
      if(count($calls["campers"]) > 0) {
         foreach($calls["campers"] as $oldcamperid => $camper) {
            $valideditor = $valideditor || $user->email == $camper->email;
         }
      }
      if($admin || $valideditor || count($calls["family"]) == 0) {
         if(count($calls["family"]) > 0) {
            foreach($calls["family"] as $id => $family) {
               $familyid = $model->upsertFamily($family);
               if($editcamper == "1") {
                  $editcamper = "($familyid)";
               }
            }
         }
         if(count($calls["campers"]) > 0) {
            foreach($calls["campers"] as $oldcamperid => $camper) {
               if($camper->attending > 0 && $camper->firstname != "" && $camper->lastname != "") {
                  $camper->familyid = $familyid;
                  $newcamperid = $model->upsertCamper($camper);
                  if(count($calls["phonenumbers"])) {
                     foreach($calls["phonenumbers"] as $phonenumber) {
                        if($phonenumber->camperid == $oldcamperid) {
                           $phonenumber->camperid = $newcamperid;
                        }
                     }
                  }
                  if(count($calls["charges"])) {
                     foreach($calls["charges"] as $charge) {
                        $charge->camperid = $newcamperid;
                     }
                  }
                  $fiscalyearid = $model->upsertFiscalyear($newcamperid, $calls["fiscalyear"][0]->postmark);
                  $model->deleteRoomtypepreferences($fiscalyearid);
                  if(count($calls["roomtype_preferences"][$oldcamperid]->buildingids) > 0) {
                     foreach(explode(",", $calls["roomtype_preferences"][$oldcamperid]->buildingids) as $choicenbr => $buildingid) {
                        $model->insertRoomtypepreferences($fiscalyearid, $choicenbr+1, $buildingid);
                     }
                  }
                  $model->deleteRoommatepreferences($fiscalyearid);
                  if(count($calls["roommate_preferences"][$oldcamperid]->names) > 0) {
                     foreach(explode(",", $calls["roommate_preferences"][$oldcamperid]->names) as $choicenbr => $name) {
                        $model->insertRoommatepreferences($fiscalyearid, $choicenbr+1, $this->getSafe(urldecode($name)));
                     }
                  }
               } else {
                  $model->removeFiscalyear($oldcamperid);
               }
            }
            $model->calculateCharges($familyid);
            if(count($calls["charges"]) > 0) {
               foreach($calls["charges"] as $charge) {
                  if($charge->amount != 0) {
                     $model->upsertCharge($charge, $newcamperid);
                  }
               }
            }
            if($this->getSafe(JRequest::getVar('paypal-amount')) != 0) {
               $msg = floatval($this->getSafe(JRequest::getVar('paypal-amount')));
               $this->assignRef("redirectAmt", $msg);
            } else {
               $msg = true;
               $this->assignRef("msg", $msg);

            }
         }
         if(count($calls["phonenumbers"]) > 0) {
            $phonenbrs = array();
            foreach($calls["phonenumbers"] as $key => $phonenumber) {
               if($phonenumber->phonenbr != "") {
                  array_push($phonenbrs, $key);
               }
            }
            $model->deleteOldPhonenumbers(implode(",", array_keys($calls["campers"])), implode(",", $phonenbrs));
            foreach($calls["phonenumbers"] as $phonenumber) {
               $model->upsertPhonenumber($phonenumber);
            }
         }
      } else {
         echo "<h2>Invalid Permissions to Update</h2>\n";
      }

      // DATA SAVED, GET NEW DATA

      $sumdays = 0;
      $year = $model->getYear();
      if($admin && preg_match('/^\d+$/', $editcamper)) {
         $this->assignRef('editcamper', $editcamper);
         $family = $model->getFamily("mf.familyid=" . $editcamper);
      } else {
         $family = $model->getFamily("mc.email='" . $user->email . "'");
      }
      $this->assignRef('family', $family);
      if($family->familyid) {
         $campers = $model->getCampers($family->familyid);
         foreach($campers as $camper) {
            $camper->phonenbrs = $model->getPhonenumbers($camper->camperid);
            $camper->fiscalyearid = $model->getFiscalyear($camper->camperid);
            if($camper->fiscalyearid) {
               $camper->roomtypes = $model->getRoomtypepreferences($camper->fiscalyearid);
               $camper->roommates = $model->getRoommatepreferences($camper->fiscalyearid);
            }
            $sumdays += $camper->days;
         }
         foreach($model->getCharges($family->familyid, $admin ? "" : "AND mc.fiscalyear=" . $year["year"]) as $charge) {
            if($charges[$charge->fiscalyear] == null) {
               $charges[$charge->fiscalyear] = array($charge);
            } else {
               array_push($charges[$charge->fiscalyear], $charge);
            }
         }
         $this->assignRef('charges', $charges);
         $credits[] = array();
         foreach($model->getCredits($family->familyid, $admin ? "" : "AND mf.fiscalyear=" . $year["year"]) as $credit) {
            if($credits[$credit->fiscalyear] == null) {
               $credits[$credit->fiscalyear] = array($credit);
            } else {
               array_push($credits[$credit->fiscalyear], $credit);
            }
         }
         foreach($model->getScholarships($family->familyid, $admin ? "" : "AND mf.fiscalyear=" . $year["year"]) as $credit) {
            if($credits[$credit->fiscalyear] == null) {
               $credits[$credit->fiscalyear] = array($credit);
            } else {
               array_push($credits[$credit->fiscalyear], $credit);
            }
         }
         $this->assignRef('credits', $credits);
      }
      $this->assignRef('campers', $campers);

      $this->assignRef('buildings', $model->getBuildings());
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
         $regcampers = $model->getRegisteredCampersByFamily("mf.familyid=" . $editcamper);
      } else {
         $regcampers = $model->getRegisteredCampersByCamper("mc.email='" . $user->email . "'");
      }
      if(JRequest::getVar('deleteMe') == "1") {
         $msg = true;
         $this->assignRef("msg", $msg);
         foreach($regcampers as $camper) {
            $model->deleteAttendees($camper->fiscalyearid);
            $model->deleteOldVolunteers($camper->fiscalyearid);
         }
      }
      if(count($calls["attendees"]) > 0) {
         foreach($calls["attendees"] as $dummy => $attendee) {
            $model->insertAttendee($attendee);
         }
      }
      if(count($calls["volunteers"]) > 0) {
         foreach($calls["volunteers"] as $dummy => $volunteer) {
            $model->insertVolunteer($volunteer);
         }
      }
      if(count($calls["scholarships"]) > 0) {
         foreach($calls["scholarships"] as $scholarshipid => $scholarship) {
            if($scholarship->registration_pct > 0 || $scholarship->housing_pct > 0) {
               $scholarship->scholarshipid = $scholarshipid;
               $model->upsertScholarship($scholarship);
            } else {
               $model->deleteScholarship($scholarshipid);
            }
         }
      }
      foreach($regcampers as $camper) {
         $camper->volunteers = $model->getVolunteers($camper->camperid);
         $camper->staff = $model->getStaff($camper->camperid);
         $camper->attendees = $model->getAttendees($camper->fiscalyearid);
         $camper->scholarshipMuusa = $model->getIndScholarships($camper->fiscalyearid, 1);
         $camper->scholarshipYmca = $model->getIndScholarships($camper->fiscalyearid, 0);
      }
      $this->assignRef('regcampers', $regcampers);
      $this->assignRef('positions', $model->getPositions(1));
      if($admin) {
         $this->assignRef('paidpositions', $model->getPositions(0));
      }
      $times = $model->getTimes();
      foreach($model->getWorkshops() as $workshop) {
         if($workshop["days"] == "MTuWThF") {
            $workshop["days"] = "5 days";
         }
         $times[$workshop["timeid"]]["shops"][$workshop["eventid"]] = $workshop;
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
