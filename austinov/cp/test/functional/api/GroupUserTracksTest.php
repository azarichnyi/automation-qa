<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

cpMemcache::getInstance()->delete('tiles1');
Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/user0');
$browser = new sfTestFunctional(new sfBrowser());

$user = UserTable::getInstance()->findAll()->toArray();
$user = reset($user);
// Clear all mess stored in redis from any previous tests
$rclient = sfRedis::getClient();
$rclient->delete('track:' . $user['sf_guard_user_id']);

$browser->info('Test verifies tracks calculation and grouping')->
  post('/user/login', array( 'auth_info' =>
                             '{"email":"johndoe","password":"123456"}'))->
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
    matches('/success\":true/')->
  end()->
  with('user')->isAuthenticated()->
  info('> Posting first track')->
  post('/track/add', array( 'points' => '{"locations": [ {
    "lat": 50.47274,           "speed": 71, "direction": 0,
      "lon": 30.51054,         "timestamp": "2011-07-28 12:41:00 +0000"},
   {"lat": 50.47264632251466,  "speed": 62, "direction": 0,
    "lon": 30.510692183640437, "timestamp": "2011-07-28 12:41:01 +0000"},
   {"lat": 50.472552645029324, "speed": 73, "direction": 0,
    "lon": 30.510844367280875, "timestamp": "2011-07-28 12:41:02 +0000"},
   {"lat": 50.472458967543986, "speed": 80, "direction": 0,
    "lon": 30.510996550921313, "timestamp": "2011-07-28 12:41:03 +0000"},
   {"lat": 50.47236529005865,  "speed": 72, "direction": 0,
    "lon": 30.51114873456175,  "timestamp": "2011-07-28 12:41:03 +0000"},
   {"lat": 50.47227161257331,  "speed": 73, "direction": 0,
    "lon": 30.51130091820219,  "timestamp": "2011-07-28 12:41:04 +0000"},
   {"lat": 50.47217793508797,  "speed": 75, "direction": 0,
    "lon": 30.511453101842626, "timestamp": "2011-07-28 12:41:05 +0000"},
   {"lat": 50.47208425760263,  "speed": 80, "direction": 0,
    "lon": 30.511605285483064, "timestamp": "2011-07-28 12:41:05 +0000"},
   {"lat": 50.47199058011729,  "speed": 76, "direction": 0,
    "lon": 30.511757469123502, "timestamp": "2011-07-28 12:41:06 +0000"},
   {"lat": 50.471909,          "speed": 74, "direction": 0,
    "lon": 30.51189,           "timestamp": "2011-07-28 12:41:07 +0000"},
   {"lat": 50.47184,           "speed": 78, "direction": 0,
     "lon": 30.511999,         "timestamp": "2011-07-28 12:41:08 +0000"},
   {"lat": 50.471779,          "speed": 63, "direction": 0,
     "lon": 30.5121,           "timestamp": "2011-07-28 12:41:08 +0000"},
   {"lat": 50.47168513172154,  "speed": 70, "direction": 0,
     "lon": 30.51225189041822, "timestamp": "2011-07-28 12:41:09 +0000"},
   {"lat": 50.47159126344309,  "speed": 64, "direction": 0,
     "lon": 30.51240378083644, "timestamp": "2011-07-28 12:41:10 +0000"},
   {"lat": 50.47149739516463,  "speed": 76, "direction": 0,
     "lon": 30.51255567125466, "timestamp": "2011-07-28 12:41:11 +0000"},
   {"lat": 50.47140352688618,  "speed": 64, "direction": 0,
     "lon": 30.51270756167288, "timestamp": "2011-07-28 12:41:12 +0000"},
   {"lat": 50.47130965860772,  "speed": 69, "direction": 0,
     "lon": 30.5128594520911,  "timestamp": "2011-07-28 12:41:12 +0000"},
   {"lat": 50.47121579032927,  "speed": 62, "direction": 0,
     "lon": 30.51301134250932, "timestamp": "2011-07-28 12:41:13 +0000"},
   {"lat": 50.471161,          "speed": 71, "direction": 0,
     "lon": 30.5131,           "timestamp": "2011-07-28 12:41:14 +0000"},
   {"lat": 50.471067191745405, "speed": 80, "direction": 0,
     "lon": 30.513251979853813,"timestamp": "2011-07-28 12:41:15 +0000"},
     {"lat": 50.470982,        "speed": 78, "direction": 0,
     "lon": 30.51339,          "timestamp": "2011-07-28 12:41:15 +0000"},
   {"lat": 50.470909,          "speed": 78, "direction": 0,
     "lon": 30.5135,           "timestamp": "2011-07-28 12:41:16 +0000"},
   {"lat": 50.470951,          "speed": 62, "direction": 0,
     "lon": 30.51358,          "timestamp": "2011-07-28 12:41:17 +0000"
  } ]}'))->
  
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
  end()->
  
  info('> Posting the second track')->
  post('/track/add', array( 'points' => '{"locations": [
    {"lat": 50.47261, "speed": 79, "direction": 0,
      "lon": 30.510189, "timestamp": "2011-07-28 13:00:00 +0000"},
    {"lat": 50.47270575720501, "speed": 72, "direction": 0,
      "lon": 30.510337955652243, "timestamp": "2011-07-28 13:00:01 +0000"},
    {"lat": 50.47279, "speed": 80, "direction": 0,
      "lon": 30.510469, "timestamp": "2011-07-28 13:00:02 +0000"},
    {"lat": 50.47269597592992, "speed": 65, "direction": 0,
      "lon": 30.510620655168648, "timestamp": "2011-07-28 13:00:02 +0000"},
    {"lat": 50.47260195185984, "speed": 82, "direction": 0,
      "lon": 30.510772310337295, "timestamp": "2011-07-28 13:00:03 +0000"},
    {"lat": 50.47250792778976, "speed": 64, "direction": 0,
      "lon": 30.510923965505942, "timestamp": "2011-07-28 13:00:04 +0000"},
    {"lat": 50.47241390371968, "speed": 67, "direction": 0,
      "lon": 30.51107562067459, "timestamp": "2011-07-28 13:00:05 +0000"},
    {"lat": 50.4723198796496, "speed": 66, "direction": 0,
      "lon": 30.511227275843236, "timestamp": "2011-07-28 13:00:06 +0000"},
    {"lat": 50.472225855579524, "speed": 63, "direction": 0,
      "lon": 30.511378931011883, "timestamp": "2011-07-28 13:00:06 +0000"},
    {"lat": 50.472131831509444, "speed": 82, "direction": 0,
      "lon": 30.51153058618053, "timestamp": "2011-07-28 13:00:07 +0000"},
    {"lat": 50.472037807439364, "speed": 76, "direction": 0,
      "lon": 30.511682241349178, "timestamp": "2011-07-28 13:00:08 +0000"},
    {"lat": 50.471943783369284, "speed": 71, "direction": 0,
      "lon": 30.511833896517825, "timestamp": "2011-07-28 13:00:09 +0000"},
    {"lat": 50.471909, "speed": 82, "direction": 0,
      "lon": 30.51189, "timestamp": "2011-07-28 13:00:09 +0000"},
    {"lat": 50.47184, "speed": 81, "direction": 0,
      "lon": 30.511999, "timestamp": "2011-07-28 13:00:10 +0000"},
    {"lat": 50.471779, "speed": 75, "direction": 0,
      "lon": 30.5121, "timestamp": "2011-07-28 13:00:11 +0000"},
    {"lat": 50.47168513172154, "speed": 79, "direction": 0,
      "lon": 30.51225189041822, "timestamp": "2011-07-28 13:00:11 +0000"},
    {"lat": 50.47159126344309, "speed": 67, "direction": 0,
      "lon": 30.51240378083644, "timestamp": "2011-07-28 13:00:12 +0000"},
    {"lat": 50.47149739516463, "speed": 66, "direction": 0,
      "lon": 30.51255567125466, "timestamp": "2011-07-28 13:00:13 +0000"},
    {"lat": 50.47140352688618, "speed": 75, "direction": 0,
      "lon": 30.51270756167288, "timestamp": "2011-07-28 13:00:14 +0000"},
    {"lat": 50.47130965860772, "speed": 79, "direction": 0,
      "lon": 30.5128594520911, "timestamp": "2011-07-28 13:00:14 +0000"},
    {"lat": 50.47121579032927, "speed": 76, "direction": 0,
      "lon": 30.51301134250932, "timestamp": "2011-07-28 13:00:15 +0000"},
    {"lat": 50.471161, "speed": 78, "direction": 0,
      "lon": 30.5131, "timestamp": "2011-07-28 13:00:16 +0000"},
    {"lat": 50.471067191745405, "speed": 71, "direction": 0,
      "lon": 30.513251979853813, "timestamp": "2011-07-28 13:00:16 +0000"},
    {"lat": 50.470982, "speed": 67, "direction": 0,
      "lon": 30.51339, "timestamp": "2011-07-28 13:00:17 +0000"},
    {"lat": 50.470909, "speed": 77, "direction": 0,
      "lon": 30.5135, "timestamp": "2011-07-28 13:00:18 +0000"},
    {"lat": 50.470959, "speed": 63, "direction": 0,
      "lon": 30.51359, "timestamp": "2011-07-28 13:00:19 +0000"},
    {"lat": 50.471008, "speed": 66, "direction": 0,
      "lon": 30.51366, "timestamp": "2011-07-28 13:00:20 +0000"}
  ]}'))->
  
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
  end()->
  
  info('> Posting the third track')->
  post('/track/add', array( 'points' => '{"locations": [
    {"lat": 50.472561,           "speed": 80, "direction": 0,
      "lon": 30.510839,          "timestamp": "2011-07-28 13:45:00 +0000"},
    {"lat": 50.472466946823104,  "speed": 64, "direction": 0,
      "lon": 30.51099061025907,  "timestamp": "2011-07-28 13:45:01 +0000"},
    {"lat": 50.47237289364621,   "speed": 67, "direction": 0,
      "lon": 30.511142220518142, "timestamp": "2011-07-28 13:45:02 +0000"},
    {"lat": 50.472278840469315,  "speed": 75, "direction": 0,
      "lon": 30.511293830777213, "timestamp": "2011-07-28 13:45:03 +0000"},
    {"lat": 50.47218478729242,   "speed": 68, "direction": 0,
      "lon": 30.511445441036283, "timestamp": "2011-07-28 13:45:03 +0000"},
    {"lat": 50.472090734115525,  "speed": 78, "direction": 0,
      "lon": 30.511597051295354, "timestamp": "2011-07-28 13:45:04 +0000"},
    {"lat": 50.47199668093863,   "speed": 67, "direction": 0,
      "lon": 30.511748661554424, "timestamp": "2011-07-28 13:45:05 +0000"},
    {"lat": 50.471909,           "speed": 72, "direction": 0,
      "lon": 30.51189,           "timestamp": "2011-07-28 13:45:06 +0000"},
    {"lat": 50.47184,            "speed": 75, "direction": 0,
      "lon": 30.511999,          "timestamp": "2011-07-28 13:45:06 +0000"},
    {"lat": 50.471779,           "speed": 76, "direction": 0,
      "lon": 30.5121,            "timestamp": "2011-07-28 13:45:07 +0000"},
    {"lat": 50.4716851011107,    "speed": 75, "direction": 0,
      "lon": 30.51225184392879,  "timestamp": "2011-07-28 13:45:08 +0000"},
    {"lat": 50.471591202221404,  "speed": 69, "direction": 0,
      "lon": 30.51240368785758,  "timestamp": "2011-07-28 13:45:09 +0000"},
    {"lat": 50.47149730333211,   "speed": 77, "direction": 0,
      "lon": 30.512555531786372, "timestamp": "2011-07-28 13:45:09 +0000"},
    {"lat": 50.47140340444281,   "speed": 80, "direction": 0,
      "lon": 30.512707375715163, "timestamp": "2011-07-28 13:45:10 +0000"},
    {"lat": 50.47130950555351,   "speed": 63, "direction": 0,
      "lon": 30.512859219643953, "timestamp": "2011-07-28 13:45:11 +0000"},
    {"lat": 50.471241,           "speed": 82, "direction": 0,
      "lon": 30.51297,           "timestamp": "2011-07-28 13:45:11 +0000"}
  ]}'))->
  
  with('response')->begin()->
    isStatusCode(200)->
    isHeader('content-type', 'application/json; charset=utf-8')->
  end()
;

//dump(shell_exec(
//     sfConfig::get('sf_root_dir').'/symfony GroupUserTracks --env=tost'));

$browser->info('Running GroupUserTracks Task');
$cnfg = ProjectConfiguration::getActive(); 
$task = new GroupUserTracksTask($cnfg->getEventDispatcher(),
                                          new sfFormatter());
$task->run(array(), array());

$q = Doctrine::getTable('Track')->createQuery('t')->where('t.user_id = ?', 1);
$tracks = $q->fetchArray();

$browser->info('> Testing points parents are not zero');
$browser->test()->isnt($tracks[0]['start_point_parent_id'],NULL,'non-NULL');
$browser->test()->isnt($tracks[0]['end_point_parent_id'],NULL,'non-NULL');
$browser->test()->isnt($tracks[1]['start_point_parent_id'],NULL,'non-NULL');
$browser->test()->isnt($tracks[1]['end_point_parent_id'],NULL,'non-NULL');
$browser->info('> Testing if points parent ids do match');
$browser->test()->is($tracks[0]['start_point_parent_id'],
                     $tracks[1]['start_point_parent_id'],
                       'start points parents do match');
$browser->test()->is($tracks[0]['end_point_parent_id'],
                     $tracks[1]['end_point_parent_id'],
                       'end points parents do match');
