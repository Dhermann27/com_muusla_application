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
      if(count($calls["family"]) > 0) {
         foreach($calls["family"] as $id => $family) {
            $familyid = $model->upsertFamily($family);
         }
      }
      if(count($calls["campers"]) > 0) {
         foreach($calls["campers"] as $oldcamperid => $camper) {
            $attending = $camper->attending;
            $camper->familyid = $familyid;
            $newcamperid = $model->upsertCamper($camper);
            foreach($calls["phonenumbers"] as $phonenumber) {
               if($phonenumber->camperid == $oldcamperid) {
                  $phonenumber->camperid = $newcamperid;
               }
            }
            $fiscalyearid = $model->upsertFiscalyear($newcamperid, $attending);
            $model->deleteRoomtypepreferences($fiscalyearid);
            foreach(explode(",", $calls["roomtype_preferences"][$oldcamperid]->buildingids) as $choicenbr => $buildingid) {
               $model->insertRoomtypepreferences($fiscalyearid, $choicenbr+1, $buildingid);
            }
            $model->deleteRoommatepreferences($fiscalyearid);
            foreach(explode(",", $calls["roommate_preferences"][$oldcamperid]->names) as $choicenbr => $name) {
               $model->insertRoommatepreferences($fiscalyearid, $choicenbr+1, $this->getSafe(urldecode($name)));
            }
            $model->deleteAttendees($fiscalyearid);
            foreach($calls["attendees"] as $timeid => $eventids) {
               foreach(explode(",", $eventids->eventids) as $choicenbr => $eventid) {
                  $model->insertAttendees($fiscalyearid, $choicenbr+1, $timeid, $eventid);
               }
            }
            $model->deleteOldVolunteers($fiscalyearid, $calls["volunteers"][0]->positionids);
            if($calls["volunteers"][0]->positionids != "") {
               $model->insertVolunteers($fiscalyearid, $calls["volunteers"][0]->positionids);
            }
         }
      }
      if(count($calls["phonenumbers"]) > 0) {
         $model->deleteOldPhonenumbers(implode(",", array_keys($calls["campers"])), implode(",", array_keys($calls["phonenumbers"])));
         foreach($calls["phonenumbers"] as $phonenumber) {
            $model->upsertPhonenumber($phonenumber);
         }
      }

      // DATA SAVED, GET NEW DATA

      $family = $model->getFamily();
      $this->assignRef('family', $family);
      if($family->familyid) {
         $campers = $model->getCampers($family->familyid);
         foreach($campers as $camper) {
            $camper->phonenbrs = $model->getPhonenumbers($camper->camperid);
            $camper->volunteers = $model->getVolunteers($camper->camperid);
            $camper->fiscalyearid = $model->getFiscalyear($camper->camperid);
            if($camper->fiscalyearid) {
               $camper->roomtypes = $model->getRoomtypepreferences($camper->fiscalyearid);
               $camper->roommates = $model->getRoommatepreferences($camper->fiscalyearid);
               $camper->attendees = $model->getAttendees($camper->fiscalyearid);
            }
         }
         $this->assignRef('charges', $model->getCharges($family->familyid));
         $this->assignRef('credits', $model->getCredits($family->familyid));
      }
      $this->assignRef('campers', $campers);
      $this->assignRef('positions', $model->getPositions());
      $this->assignRef('buildings', $model->getBuildings());
      $this->assignRef('states', $model->getStates());
      $this->assignRef('foodoptions', $model->getFoodoptions());
      $this->assignRef('smokingoptions', $model->getSmokingoptions());
      $this->assignRef('churches', $model->getChurches());
      $this->assignRef('phonetypes', $model->getPhonetypes());
      $this->assignRef('programs', $model->getPrograms());
      $this->assignRef('year', $model->getYear());
      $times = $model->getTimes();
      foreach($model->getWorkshops() as $workshop) {
         if($workshop["days"] == "MTuWThF") {
            $workshop["days"] = "5 days";
         }
         $times[$workshop["timeid"]]["shops"][$workshop["eventid"]] = $workshop;
      }
      $this->assignRef('times', $times);

      $emptyPhonenumber = new stdClass;
      $emptyPhonenumber->phonenbrid = 0;
      $emptyPhonenumber->phonetypeid = 0;
      $this->assignRef('emptyPhonenumber', $emptyPhonenumber);

      parent::display($tpl);
   }

   function detail($tpl = null) {
      // 		$model =& $this->getModel();
      // 		$user =& JFactory::getUser();
      // 		$calls[][] = array();
      // 		$phonenumbers[] = array();
      // 		if($calls["campers"][0]) {
      // 			$hohid = $model->upsertCamper($calls["campers"][0]);
      // 			foreach($calls[phonenumbers] as $id => $number) {
      // 				if($number->camperid == 0 && $number->phonenbr != "") {
      // 					$number->camperid = $hohid;
      // 					$number->phonenbrid = $phonenbrid;
      // 					$model->upsertPhonenumber($number);
      // 				}
      // 			}
      // 			$calls[volunteers][$hohid] = $calls[volunteers][0];
      // 			unset($calls[volunteers][0]);
      // 			$model->upsertVolunteers($hohid, $calls[volunteers][$hohid]);
      // 			$hoh = $calls["campers"][0];
      // 			unset($calls["campers"][0]);
      // 		} else {
      // 			$hohid = JRequest::getSafe("hohid");
      // 		}
      // 		if(count($calls["campers"]) > 0) {
      // 			foreach($calls["campers"] as $id => $camper) {
      // 				if($camper->firstname != "") {
      // 					if($id < 1000) {
      // 						$camper->hohid = $hohid;
      // 						$camper->address1 = $hoh->address1;
      // 						$camper->address2 = $hoh->address2;
      // 						$camper->city = $hoh->city;
      // 						$camper->statecd = $hoh->statecd;
      // 						$camper->zipcd = $hoh->zipcd;
      // 						$camper->country = $hoh->country;
      // 						$camper->is_ecomm = $hoh->is_ecomm;
      // 						$camper->roompref1 = $hoh->roompref1;
      // 						$camper->roompref2 = $hoh->roompref2;
      // 						$camper->roompref3 = $hoh->roompref3;
      // 						$camper->matepref1 = $hoh->matepref1;
      // 						$camper->matepref2 = $hoh->matepref2;
      // 						$camper->matepref3 = $hoh->matepref3;
      // 						$camper->is_ymca = $hoh->is_ymca;
      // 						$camper->churchid = $hoh->churchid;
      // 					} else {
      // 						$camper->camperid = $id;
      // 					}
      // 					$camperid = $model->upsertCamper($camper);
      // 					if($calls[phonenumbers]) {
      // 						foreach($calls[phonenumbers] as $phonenbrid => $number) {
      // 							if($number->camperid == $id && $number->phonenbr != "") {
      // 								if($number->delete == "delete") {
      // 									$model->deletePhonenumber($phonenbrid);
      // 								} else {
      // 									$number->phonenbrid = $phonenbrid;
      // 									$number->camperid = $camperid;
      // 									$model->upsertPhonenumber($number);
      // 								}
      // 							}
      // 						}
      // 					}
      // 					if(!$camper->notattending) {
      // 						$model->upsertVolunteers($camperid, $calls[volunteers][$id]);
      // 					}
      // 				}
      // 			}
      // 		}

      // 		$this->assignRef('campers', $model->getFamily());
      parent::display($tpl);
   }

   function payment($tpl = null) {
      // 		$model =& $this->getModel();
      // 		$events = array();
      // 		foreach(JRequest::get() as $key=>$value) {
      // 			if(preg_match('/^selected-(\d+)-(\d+)$/', $key, $objects)) {
      // 				$events[$objects[2]][$objects[1]] = is_array($value) ? $value : array($value);
      // 			}
      // 		}
      // 		foreach($events as $camperid => $event) {
      // 			$model->upsertAttendees($camperid, $event);
      // 		}

      // 		$camper = $model->calculateCharges();
      // 		if($camper) {
      // 			$this->assignRef('hohid', $camper->hohid);
      // 			$this->assignRef('charges', $model->getCharges($camper->camperid));
      // 			$this->assignRef('credits', $model->getCredits($camper->camperid));
      // 		}

      // 		parent::display($tpl);
   }

   function getSafe($obj)
   {
      return htmlspecialchars(trim($obj), ENT_QUOTES);
   }

}
?>
