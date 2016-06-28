<?php
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Math\Rand;
use CremationPlan\Model\Find;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;
use EventCalendar\Mapper\EventsInterface;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;
use Zend\Mail;


error_reporting(0);
class IndexController extends AbstractActionController {

    protected $cmsTable;
	 protected $deathUserTable;
    protected $deathUserMetaTable;
    protected $userTable;
    protected $uploadTable;
    protected $imageUploadTable;
    protected $usertributesTable;
    protected $userPrivacyTable;
    protected $userfamilydetailsTable;
    protected $orbituary;
    protected $searchObituaryTable;
    protected $deathUserGuestBookTable;
    protected $deathUserTributeTable;
    protected $deathUserDonateTable;
    protected $languageTextTable;
    protected $deathUserLanguageTextTable;
	 protected $eventMapper;
	 protected $causeofDeathTable;
	 protected $countryofDeathTable;
	
	
	// public function indexAction()
    // {
        // if ($this->zfcUserAuthentication()->hasIdentity()) {
        // $link = $this->url()->fromRoute('home');
        // return $this->redirect()->toUrl($link);
        // }else{            
        // return $this->redirect()->toUrl('user/login');  
        // }
    // }
	
	 
    public function indexAction() {
    	
        $request = $this->getRequest();

        $country_session = new Container('base');
        if ($request->isPost()) {

            $data = $request->getPost();

            if ($data['action'] == 'country') {

                $country_session->country = $data['country'];
            }

            exit;
        }
        
        $cremation = $this->getCmsTable()->getFrontPage('cremation-planning');
        $preplanning = $this->getCmsTable()->getFrontPage('pre-planning');
        $memoralize = $this->getCmsTable()->getFrontPage('memoralize');
        $search = $this->getCmsTable()->getFrontPage('search');
       
//print_r($cremation);exit;
        return array('cremation' => $cremation, 'preplanning' => $preplanning, 'memoralize' => $memoralize, 'search' => $search);
    }

    public function SearchAction() {

		  $obituaryCnt=$this->getSearchObituaryTable()->getObituaryCount();
		  $obituary=$this->getSearchObituaryTable()->getfetch();
		  $memorialCnt=$this->getSearchObituaryTable()->getMemorialCount();
		  $memorial=$this->getSearchObituaryTable()->getMemorialfetch();
		  $country=$this->getSearchObituaryTable()->getCountriesfetch();
		  //$uscities=$this->getSearchObituaryTable()->getCitiesfetch();
		  //$specialCat=$this->getSearchObituaryTable()->getSpecialCategories();
		  $spmention=$this->getSearchObituaryTable()->getSpmentionfetch();
		  $spObituary=$this->getSearchObituaryTable()->getSpObituaryfetch(6);
		  $spMemorial=$this->getSearchObituaryTable()->getSpMemorialfetch(6);
		  $causeNames = $this->getCauseofDeathTable()->getCauseofDeathNames();
		  $cities=array_unique($uscities);
		//print_r($spObituary);exit;
		
        return new ViewModel(array('obituaryCnt'=>$obituaryCnt,
        'obituary' => $obituary,
        'memorialCnt'=>$memorialCnt,
        'memorial' => $memorial,
        //'states'=>$cities,
        'country' => $country,
        'spmention'=>$spmention,
        'spMemorial'=>$spMemorial,
        'spObituary'=>$spObituary,
        'deathcause'=>$causeNames
        
        ));
    }
public function StatesAction() {
			$request = $this->getRequest();
			if ($request->isPost()) {
			$data = $request->getPost();
         $usstates=$this->getSearchObituaryTable()->getStatesFetch($data['country']);
			$states=array_unique($usstates);
			asort($states);
			echo json_encode($states,JSON_FORCE_OBJECT);
			 }
}
 public function CityAction() {
 	//echo "ssss";exit;
			 $request = $this->getRequest();
			 if ($request->isPost()) {
			 $data = $request->getPost();
			 $uscities=$this->getSearchObituaryTable()->getCityFetch($data['state']);
			$city=array_unique($uscities);
			asort($city);
			echo json_encode($city,JSON_FORCE_OBJECT);
			 }
    }
 public function PostalcodeAction() {
 			 $request = $this->getRequest();
			 if ($request->isPost()) {
			 $data = $request->getPost();
			 $uspostalcode=$this->getSearchObituaryTable()->getPostalCodeFetch($data['city'],$data['state']);
			$postalcode=array_unique($uspostalcode);
			asort($postalcode);
			echo json_encode($postalcode,JSON_FORCE_OBJECT);
			 }
			
		 
    }
public function SearchfilterAction() {
 			 $request = $this->getRequest();
			 if ($request->isPost()) {
			 
			 $data = $request->getPost();
			 $obituary=$this->getSearchObituaryTable()->getObituarySearchfilterFetch($data);
			 $obituaryCnt=$this->getSearchObituaryTable()->getObituarySearchfilterFetchCount($data);
				//print_r($obituaryCnt[0]['obCount']);exit;			 
			 $memorial=$this->getSearchObituaryTable()->getMemorialSearchfilterFetch($data);
			 $memorialCnt=$this->getSearchObituaryTable()->getMemorialSearchfilterFetchCount($data);
 			$spmention=$this->getSearchObituaryTable()->getSpmentionfetch();
 			
		  $spObituary=$this->getSearchObituaryTable()->getSPObituarySearchfilterFetch($data);
		  $spMemorial=$this->getSearchObituaryTable()->getSPMemorialSearchfilterFetch($data);
		  $causeNames = $this->getCauseofDeathTable()->getCauseofDeathNames();
 			//$states=$this->getSearchObituaryTable()->getCitiesfetch();
 			$country=$this->getSearchObituaryTable()->getCountriesfetch();
			$country=array_unique($country);	
		if($data['country']!="") {	
			 $usstates=$this->getSearchObituaryTable()->getStatesBYCountryid($data['country']);
			$states=array_unique($usstates);
			asort($states);
			$data['stateslist']= json_encode($states,JSON_FORCE_OBJECT);
			
		
		}
		//echo $data['state'];exit;
		if($data['state']!="") {	
			 $uscities=$this->getSearchObituaryTable()->getCityFetch($data['state']);
			$city=array_unique($uscities);
			asort($city);
			$data['citylist']= json_encode($city,JSON_FORCE_OBJECT);
			
		
		}
		if($data['cities']!="") {	
			 $uspostalcode=$this->getSearchObituaryTable()->getPostalCodeFetch($data['cities'],$data['state']);
			$postalcode=array_unique($uspostalcode);
			asort($postalcode);
			$data['postallist']= json_encode($postalcode,JSON_FORCE_OBJECT);
					
		}
			return new ViewModel(array(
		  'obituaryCnt'=>$obituaryCnt,
        'obituary' => $obituary,
        'memorialCnt'=>$memorialCnt,
        'memorial' => $memorial,
        //'states'=>$states,
        'country' => $country,
        'postdata'=>$data,
        'spmention'=>$spmention,
        'spMemorial'=>$spMemorial,
        'spObituary'=>$spObituary,
        'deathcause'=>$causeNames
        ));
			 }else {
			return $this->redirect()->toRoute('search');
			
			 }
    }
    public function GetkeywordsAction(){
    
    	$request = $this->getRequest();
		if ($request->isPost()) {
			 $data = $request->getPost();
    	 	 $obituary=$this->getSearchObituaryTable()->getKeywordsfetch($data['keywords']);
    	    echo json_encode($obituary,JSON_FORCE_OBJECT);
    	}
    }
    public function ObituaryPaginationAction(){
    
    	$request = $this->getRequest();
			 if ($request->isPost()) {
			 $data = $request->getPost();
    	 $obituary=$this->getSearchObituaryTable()->getObPaginationfetch($data['count'],$data['limit']);
    	 echo json_encode($obituary,JSON_FORCE_OBJECT);
    	}
    }
    public function MemorialPaginationAction(){
    
    	$request = $this->getRequest();
			 if ($request->isPost()) {
			 $data = $request->getPost();
    	 $memorial=$this->getSearchObituaryTable()->getMmPaginationfetch($data['count'],$data['limit']);
    	 echo json_encode($memorial,JSON_FORCE_OBJECT);
    	}
    }
    public function specialMentionObPaginationAction(){
    $request = $this->getRequest();
			 if ($request->isPost()) {
			 $data = $request->getPost();
    	 $memorial=$this->getSearchObituaryTable()->getSpObPaginationfetch($data['spid'],$data['count'],$data['limit']);
    	 echo json_encode($memorial,JSON_FORCE_OBJECT);
    	}
    }
    public function specialMentionMmPaginationAction(){
    $request = $this->getRequest();
			 if ($request->isPost()) {
			 $data = $request->getPost();
    	 $memorial=$this->getSearchObituaryTable()->getSpMmPaginationfetch($data['spid'],$data['count'],$data['limit']);
    	 echo json_encode($memorial,JSON_FORCE_OBJECT);
    	}
    }
   public function viewAllSpObituaryAction() {
   	$causeNames = $this->getCauseofDeathTable()->getCauseofDeathNames();
   	$request = $this->getRequest();
			 if ($request->isPost()) {
			 
			 $data = $request->getPost();
			 
			 //$data['country']='USA';
			 $spmention=$this->getSearchObituaryTable()->getSpmentionfetch();
			
    		$spObituary=$this->getSearchObituaryTable()->getViewAllSPObituarySearchfilterFetch($data);
    		$spObituaryCnt=$this->getSearchObituaryTable()->getSPObituarySearchfilterFetchCount($data);
		  //	$states=$this->getSearchObituaryTable()->getCitiesfetch();
			//$states=array_unique($states);	
			$country=$this->getSearchObituaryTable()->getCountriesfetch();
			$country=array_unique($country);	
		if($data['country']!="") {	
			 $usstates=$this->getSearchObituaryTable()->getStatesBYCountryid($data['country']);
			$states=array_unique($usstates);
			asort($states);
			$data['stateslist']= json_encode($states,JSON_FORCE_OBJECT);
			
		
		}
			if($data['state']!="") {	
			 $uscities=$this->getSearchObituaryTable()->getCityFetch($data['state']);
			$city=array_unique($uscities);
			asort($city);
			$data['citylist']= json_encode($city,JSON_FORCE_OBJECT);
			
		
		}
		if($data['cities']!="") {	
			 $uspostalcode=$this->getSearchObituaryTable()->getPostalCodeFetch($data['cities'],$data['state']);
			$postalcode=array_unique($uspostalcode);
			asort($postalcode);
			$data['postallist']= json_encode($postalcode,JSON_FORCE_OBJECT);
					
		}
		return new ViewModel(array(
		  //'states'=>$states,
		  'country' => $country,
        'postdata'=>$data,
        'spmention'=>$spmention,
        'spObituaryCnt'=>$spObituaryCnt,
        'spObituary'=>$spObituary,
        'deathcause'=>$causeNames
        ));
     }else {
$memorial_id = $this->params()->fromQuery('id');
//$data['country']='USA';
     	$data['specialMention']=$memorial_id;
     	$spmention=$this->getSearchObituaryTable()->getSpmentionfetch();
		  //$spObituary=$this->getSearchObituaryTable()->getSpObituaryfetch(6);
		  $spObituary=$this->getSearchObituaryTable()->getViewAllSPObituarySearchfilter($data);
		  $spObituaryCnt=$this->getSearchObituaryTable()->getSPObituarySearchfilterFetchCnt($data);
		  //$states=$this->getSearchObituaryTable()->getCitiesfetch();
			//$states=array_unique($states);
			$country=$this->getSearchObituaryTable()->getCountriesfetch();
			$country=array_unique($country);	
	   	if(!$memorial_id){
				return $this->redirect()->toRoute('search');
			}     
		return new ViewModel(array(
		  //'states'=>$states,
		  'country' => $country,
        'spmention'=>$spmention,
        'postdata'=>$data,
        'spObituary'=>$spObituary,
        'spObituaryCnt'=>$spObituaryCnt,
        'specailid'=>$memorial_id,
        'deathcause'=>$causeNames
        ));
     }
	}
	public function viewAllSpMemorialAction() {
		$causeNames = $this->getCauseofDeathTable()->getCauseofDeathNames();
   	$request = $this->getRequest();
			 if ($request->isPost()) {
			 
			 $data = $request->getPost();
			 $spmention=$this->getSearchObituaryTable()->getSpmentionfetch();
			
		   $spMemorial=$this->getSearchObituaryTable()->getViewAllSPMemorialSearchfilterFetch($data);
		   $spMemorialCnt=$this->getSearchObituaryTable()->getViewAllSPMemorialSearchfilterFetchCount($data);
 			//$states=$this->getSearchObituaryTable()->getCitiesfetch();
			//$states=array_unique($states);	
			$country=$this->getSearchObituaryTable()->getCountriesfetch();
			$country=array_unique($country);	
		if($data['country']!="") {	
			 $usstates=$this->getSearchObituaryTable()->getStatesBYCountryid($data['country']);
			$states=array_unique($usstates);
			asort($states);
			$data['stateslist']= json_encode($states,JSON_FORCE_OBJECT);
			
		
		}
			if($data['state']!="") {	
			 $uscities=$this->getSearchObituaryTable()->getCityFetch($data['state']);
			$city=array_unique($uscities);
			asort($city);
			$data['citylist']= json_encode($city,JSON_FORCE_OBJECT);
			
		
		}
		if($data['cities']!="") {	
			 $uspostalcode=$this->getSearchObituaryTable()->getPostalCodeFetch($data['cities'],$data['state']);
			$postalcode=array_unique($uspostalcode);
			asort($postalcode);
			$data['postallist']= json_encode($postalcode,JSON_FORCE_OBJECT);
					
		}
		return new ViewModel(array(
		  //'states'=>$states,
		  'country' => $country,
        'postdata'=>$data,
        'spmention'=>$spmention,
		  'spMemorialCnt'=>$spMemorialCnt,
        'spMemorial'=>$spMemorial,
        'deathcause'=>$causeNames
        
        ));
     }else {
	$memorial_id = $this->params()->fromQuery('id');
     	//$data['country']='USA';
     	$data['specialMention']=$memorial_id;
     	$spmention=$this->getSearchObituaryTable()->getSpmentionfetch();
		   $spMemorial=$this->getSearchObituaryTable()->getViewAllSPMemorialSearchfilter($data);
		   $spMemorialCnt=$this->getSearchObituaryTable()->getViewAllSPMemorialSearchfilterFetchCnt($data);
		   //$states=$this->getSearchObituaryTable()->getCitiesfetch();
			//$states=array_unique($states);
			$country=$this->getSearchObituaryTable()->getCountriesfetch();
			$country=array_unique($country);
	   	if(!$memorial_id){
				return $this->redirect()->toRoute('search');
			}     
		return new ViewModel(array(
		  //'states'=>$states,
		  'country' => $country,
        'spmention'=>$spmention,
        'postdata'=>$data,
        'spMemorial'=>$spMemorial,
		  'spMemorialCnt'=>$spMemorialCnt,
        'specailid'=>$memorial_id,
        'deathcause'=>$causeNames
        ));
     }
	}    	
    public function ViewAllObituaryAction() {
    	$request = $this->getRequest();
    	 $causeNames = $this->getCauseofDeathTable()->getCauseofDeathNames();
			 if ($request->isPost()) {
			
			 $data = $request->getPost();
			 $obituary=$this->getSearchObituaryTable()->getAllObituaryFetch($data);
			 $obituaryCnt=$this->getSearchObituaryTable()->getObituarySearchfilterFetchCount($data);
			
 			//$states=$this->getSearchObituaryTable()->getCitiesfetch();
			//$states=array_unique($states);	
		   $country=$this->getSearchObituaryTable()->getCountriesfetch();
			$country=array_unique($country);
		if($data['country']!="") {	
			 $usstates=$this->getSearchObituaryTable()->getStatesBYCountryid($data['country']);
			$states=array_unique($usstates);
			asort($states);
			$data['stateslist']= json_encode($states,JSON_FORCE_OBJECT);
		}
		if($data['state']!="") {	
			 $uscities=$this->getSearchObituaryTable()->getCityFetch($data['state']);
			$city=array_unique($uscities);
			asort($city);
			$data['citylist']= json_encode($city,JSON_FORCE_OBJECT);
			
		
		}
		if($data['cities']!="") {	
			 $uspostalcode=$this->getSearchObituaryTable()->getPostalCodeFetch($data['cities'],$data['state']);
			$postalcode=array_unique($uspostalcode);
			asort($postalcode);
			$data['postallist']= json_encode($postalcode,JSON_FORCE_OBJECT);
					
		}
			return new ViewModel(array(
		  'obituaryCnt'=>$obituaryCnt,
        'obituary' => $obituary,
        'postdata'=>$data,
        //'states'=>$states
        'country' => $country,
        'deathcause'=>$causeNames
        ));
		}else {
    	  $obituary=$this->getSearchObituaryTable()->getAllObituaries();
		  $obituaryCnt=$this->getSearchObituaryTable()->getObituaryCount();
		  //$states=$this->getSearchObituaryTable()->getCitiesfetch();
		  //$states=array_unique($states);
		  $country=$this->getSearchObituaryTable()->getCountriesfetch();
		  $country=array_unique($country);
        return new ViewModel(array(
        'obituaryCnt'=>$obituaryCnt,
        'obituary' => $obituary,
        //'states'=>$states
        'country' => $country,
        'deathcause'=>$causeNames
        ));	  
     }  
    }
    public function ViewAllMemorialAction() {
    	$causeNames = $this->getCauseofDeathTable()->getCauseofDeathNames();
    	$request = $this->getRequest();
			 if ($request->isPost()) {
			
			 $data = $request->getPost();
			 $memorial=$this->getSearchObituaryTable()->getAllMemorialFetch($data);
			 $memorialCnt=$this->getSearchObituaryTable()->getMemorialSearchfilterFetchCount($data);
			 $country=$this->getSearchObituaryTable()->getCountriesfetch();
			 $country=array_unique($country);
 			//$states=$this->getSearchObituaryTable()->getCitiesfetch();
			//$states=array_unique($states);	
		if($data['country']!="") {	
			 $usstates=$this->getSearchObituaryTable()->getStatesBYCountryid($data['country']);
			$states=array_unique($usstates);
			asort($states);
			$data['stateslist']= json_encode($states,JSON_FORCE_OBJECT);
			
		
		}
		if($data['state']!="") {	
			 $uscities=$this->getSearchObituaryTable()->getCityFetch($data['state']);
			$city=array_unique($uscities);
			asort($city);
			$data['citylist']= json_encode($city,JSON_FORCE_OBJECT);
			
		
		}
		if($data['cities']!="") {	
			 $uspostalcode=$this->getSearchObituaryTable()->getPostalCodeFetch($data['cities'],$data['state']);
			$postalcode=array_unique($uspostalcode);
			asort($postalcode);
			$data['postallist']= json_encode($postalcode,JSON_FORCE_OBJECT);
					
		}
			return new ViewModel(array(
		  'memorialCnt'=>$memorialCnt,
        'memorial' => $memorial,
        'postdata'=>$data,
        //'states'=>$states
        'country' => $country,
        'deathcause'=>$causeNames
        ));
		}else {
		  $memorial=$this->getSearchObituaryTable()->getAllMemorials();
 			$memorialCnt=$this->getSearchObituaryTable()->getMemorialCount();
			
 			//$states=$this->getSearchObituaryTable()->getCitiesfetch();
			//$states=array_unique($states);	
		  	$country=$this->getSearchObituaryTable()->getCountriesfetch();
			$country=array_unique($country);
        return new ViewModel(array(
			'memorialCnt'=>$memorialCnt,
         'memorial' => $memorial,
         //'states'=>$states
         'country' => $country,
         'deathcause'=>$causeNames
        ));	
     }    
    }
   /* public function cityAction() {

        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $request->getPost();

            $url = 'http://api.wunderground.com/auto/wui/geo/WXCurrentObXML/index.xml?query=' . $data['lat'] . ',' . $data['lang'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $xml = simplexml_load_string(curl_exec($ch));
            $json = json_encode($xml);
            $arr = json_decode($json, true);

            $weather = $arr['weather'];
            $dateAndTime = explode(',', $arr['local_time']);
            $date = date("d:m:Y", strtotime($dateAndTime[0] . ' ' . date('Y')));
            $time = date("H:m A", strtotime($dateAndTime[1]));

            $citySession = new Container('base');

            $citySession->country = $arr['display_location']['country'];
            $citySession->city = $arr['display_location']['city'];
            $citySession->weather = $weather;
            $citySession->date = $date;
            $citySession->time = $time;
           
            return $this->redirect()->toRoute('application');
        }
    }*/

 /*   public function statesAction() {
        $country = '';
        $countrySession = new Container('base');

        if ($countrySession->offsetExists('country')) {
            $country = $countrySession->country;
        }

        if ($country == '' || $country == 'usa') {

            $stateList = array('Albany', 2 => 'Alexandria', 3 => 'Allentown', 4 => 'Altoona-Johnstown', 5 => 'Amarillo', 6 => 'Anchorage', 7 => 'Arlington', 8 => 'Atlanta', 9 => 'Austin', 10 => 'Baker', 11 => 'Baltimore', 12 => 'Baton Rouge', 13 => 'Beaumont', 14 => 'Belleville', 15 => 'Biloxi', 16 => 'Birmingham', 17 => 'Bismarck', 18 => 'Boise', 19 => 'Boston', 20 => 'Bridgeport', 21 => 'Brooklyn', 22 => 'Brownsville', 23 => 'Buffalo', 24 => 'Burlington', 25 => 'Camden', 26 => 'Charleston', 27 => 'Charlotte', 28 => 'Cheyenne', 29 => 'Chicago', 30 => 'Cincinnati', 31 => 'Cleveland', 32 => 'Colorado Springs', 33 => 'Columbus', 34 => 'Corpus Christi', 35 => 'Covington', 36 => 'Crookston', 37 => 'Dallas', 38 => 'Davenport', 39 => 'Denver', 40 => 'Des Moines', 41 => 'Detroit', 42 => 'Dodge City', 43 => 'Dubuque', 44 => 'Duluth', 45 => 'Edmonton (Cananda)', 46 => 'El Paso', 47 => 'Erie', 48 => 'Evansville', 49 => 'Fairbanks', 50 => 'Fall River', 51 => 'Fargo', 52 => 'Fort Wayne-South Bend', 53 => 'Fort Worth', 54 => 'Fresno', 55 => 'Gallup', 56 => 'Galveston-Houston', 57 => 'Gary', 58 => 'Gaylord', 59 => 'Grand Island', 60 => 'Grand Rapids', 61 => 'Great Falls-Billings', 62 => 'Green Bay', 63 => 'Greensburg', 64 => 'Harrisburg', 65 => 'Hartford', 66 => 'Helena', 67 => 'Honolulu', 68 => 'Houma-Thibodaux', 69 => 'Indianapolis', 70 => 'Jackson', 71 => 'Jefferson City', 72 => 'Joliet', 73 => 'Juneau', 74 => 'Kalamazoo', 75 => 'Kansas City', 76 => 'Kansas City-St. Joseph', 77 => 'Knoxville', 78 => 'La Crosse', 79 => 'Lafayette', 80 => 'Lafayette in Indiana', 81 => 'Lake Charles', 82 => 'Lansing', 83 => 'Laredo', 84 => 'Las Cruces', 85 => 'Las Vegas', 86 => 'Lexington', 87 => 'Lincoln', 88 => 'Little Rock', 89 => 'Los Angeles', 90 => 'Louisville', 91 => 'Lubbock', 92 => 'Madison', 93 => 'Manchester', 94 => 'Marquette', 95 => 'Memphis', 96 => 'Metuchen', 97 => 'Miami', 98 => 'Military Services', 99 => 'Milwaukee', 100 => 'Mobile', 101 => 'Monterey', 102 => 'Nashville', 103 => 'New Orleans', 104 => 'New Ulm', 105 => 'New York', 106 => 'Newark', 107 => 'Newton for Melkites', 108 => 'Norwich', 109 => 'Oakland', 110 => 'Ogdensburg', 111 => 'Oklahoma City', 112 => 'Omaha', 113 => 'Orange', 114 => 'Orlando', 115 => 'Our Lady of Deliverance of Newark for Syrians', 116 => 'Our Lady of Lebanon of L.A. for Maronites', 117 => 'Owensboro', 118 => 'Palm Beach', 119 => 'Paterson', 120 => 'Pensacola-Tallahassee', 121 => 'Peoria', 122 => 'Philadelphia', 123 => 'Philadelphia for Ukrainians', 124 => 'Phoenix', 125 => 'Pittsburgh', 126 => 'Portland in Maine', 127 => 'Portland in Oregon', 128 => 'Providence', 129 => 'Pueblo', 130 => 'Raleigh', 131 => 'Rapid City', 132 => 'Reno', 133 => 'Richmond', 134 => 'Rochester', 135 => 'Rockford', 136 => 'Rockville Centre', 137 => 'Sacramento', 138 => 'Saginaw', 139 => 'Salina', 140 => 'Salt Lake City', 141 => 'San Angelo', 142 => 'San Antonio', 143 => 'San Bernardino', 144 => 'San Diego', 145 => 'San Francisco', 146 => 'San Jose', 147 => 'Santa Fe', 148 => 'Santa Rosa', 149 => 'Savannah', 150 => 'Scranton', 151 => 'Seattle', 152 => 'Shreveport', 153 => 'Sioux City', 154 => 'Sioux Falls', 155 => 'Spokane', 156 => 'Springfield in Illinois', 157 => 'Springfield in Massachusetts', 158 => 'Springfield-Cape Girardeau', 159 => 'St. Augustine', 160 => 'St. Cloud', 161 => 'St. Josaphat of Parma for Ukrainians', 162 => 'St. Louis', 163 => 'St. Maron of Brooklyn for the Maronites', 164 => 'St. Nicholas of Chicago for Ukrainians', 165 => 'St. Paul-Minneapolis', 166 => 'St. Petersburg', 167 => 'St. Thomas the Apostle of Chicago-Syro-Malabars', 168 => 'St. Thomas, VI', 169 => 'Stamford for Ukrainians', 170 => 'Steubenville', 171 => 'Stockton', 172 => 'Superior', 173 => 'Syracuse', 174 => 'Toledo', 175 => 'Trenton', 176 => 'Tucson', 177 => 'Tulsa', 178 => 'Tyler', 179 => 'Venice', 180 => 'Victoria', 181 => 'Washington', 182 => 'Wheeling-Charleston', 183 => 'Wichita', 184 => 'Wilmington', 185 => 'Winona', 186 => 'Worcester', 187 => 'Yakima', 188 => 'Youngstown');
        } else if ($country == 'india') {

            $stateList = array(
                'AP' => 'Mumbai',
                'AR' => 'Delhi',
                'AS' => 'Bangalore',
                'BR' => 'Hyderabad',
                'CT' => 'Ahmedabad',
                'GA' => 'Chennai',
                'GJ' => 'Kolkata',
                'HR' => 'Surat',
                'HP' => 'Pune',
                'JK' => 'Jaipur',
                'JH' => 'Lucknow',
                'KA' => 'Kanpur',
                'KL' => 'Nagpur',
                'MP' => 'Coimbatore',
                'MH' => 'Guwahati',
                'MN' => 'Madurai',
                'ML' => 'Guntur',
                'MZ' => 'Mangalore',
                'NL' => 'Cochi ',
                'OR' => 'Agra',
                'PB' => 'Rameswaram',
                'RJ' => 'Kasi',
                'SK' => 'Haridwar',
                'TN' => 'Puri',
                'TR' => 'Shiridi',
                'UK' => 'Ujjain',
                'UP' => 'Varanasi',
                'WB' => 'Thirupati',
                'WB' => 'Mathura',
            );
        }
        $options = '';
        foreach ($stateList as $state) {

            $options .= '<option value="' . $state . '">' . $state . '</option>';
        }
        echo $options;
        exit;
    }
	*/
	
	// add this
	public function checkAction()
	{
		die;
	}
	
	public function antimAction()
    {
        if ($this->zfcUserAuthentication()->hasIdentity()) {
        $link = $this->url()->fromRoute('add-death-user');
        return $this->redirect()->toUrl($link);
        }else{            
          return $this->redirect()->toUrl('user/login');  
        }
    }
	
	// edit action 
	
	public function editEmployementAction(){
	
	if ($this->zfcUserAuthentication()->hasIdentity()) {
    $current_user_id=$this->zfcUserAuthentication()->getIdentity()->getId();
	// print_r($current_user_id);die;
    }
	
	
	
    if(empty($userInfo)){
    return $this->redirect()->toRoute('edit-profile');   
    }
    $request =$this->getRequest();
   if($request->isPost())
    {
	// if($EmployerSession_id != )
	$user = $request->getPost('user');
	$this->getDeathUserMetaTable()->saveMeta($user,$userInfo['deathuser_id']);
	$employer_session = new Container('employer');
	$employer_session->employe_id =$userInfo['deathuser_id'];
	$EmployerSession_id =  $employer_session->employe_id ;
	$empl = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($EmployerSession_id);
	
	
	$link = $this->url()->fromRoute('edit-profile',array(),array('force_canonical' => true)).'?step='.$token.'&pos=9';
           return $this->redirect()->toUrl($link);
        } 
		$this->layout('layout/custom');	
	}
	
	
public function addDeathUserAction()
    {

      $obisuary_session = new Container('obituaries');
      $obisuray_name = $obisuary_session->orbitory1;
		$spmention=$this->getSearchObituaryTable()->getSpmentionfetch();
	   $getschoolname = $this->getSchoolTable()->school_name(); 
		$college_name1 = $this->getCollegeTable()->college_name_all();
		$university_name1 = $this->getUniversityTable()->university_name_all();
		$othertable = $this->getOtherTable()->other_name_all();
		$causeNames = $this->getCauseofDeathTable()->getCauseofDeathNames();
		$countriesNames = $this->getCountryofDeathTable()->getCountryofDeathNames();
	    // print "<pre>";
	    // print_r($othertable);die;
	if ($this->zfcUserAuthentication()->hasIdentity()) {
    $current_user_id=$this->zfcUserAuthentication()->getIdentity()->getId();
    //echo $current_user_id;exit;
    }
    //$beforelogin_session = new Container('beforeuser');
 //print_r($beforelogin_session->userdet);exit;
   /* else 
    return $this->redirect()->toUrl('user/login');*/ 
    $token = $this->params()->fromQuery('step');
	if(!empty($token)){
    $userInfo = $this->getDeathUserTable()->getUser($token);
    
    if(empty($userInfo)){
    //$this->flashMessenger()->addErrorMessage('Internal Server error raised please contact to site administration');

    return $this->redirect()->toRoute('home');   
  
    }
}
 
    $request =$this->getRequest();
    if($request->isPost())
    {
    	   
	$user = $request->getPost('user');
	
	//print_r($user);die;
	if(empty($token))
		{
			if ($this->zfcUserAuthentication()->hasIdentity()) {
    $current_user_id=$this->zfcUserAuthentication()->getIdentity()->getId();
    //echo $current_user_id;exit;
    }
    else {
    	
    return $this->redirect()->toUrl('user/login');
 }
 
      $user = $request->getPost('user');
    	$langt = $request->getPost('lang');
		$token = $this->createToken();
		$user['steptoken'] =$token;
        $user['user_id'] =$current_user_id;
		$user['obituary'] = $obisuray_name;
		$draft = $request->getPost('draft_info');
		
		if($draft == 'draft_info')
		{
			$user['submit'] = 'draft';	
		}
		else
		{
			$user['submit'] = 'save';
		}
	
     $user_id = $this->getDeathUserTable()->saveDeathUser($user);
	 $user_session = new Container('user');
	 $user_session->user_id =$user_id;
	 $dlangtextid= $this->getDeathUserLanguageTextTable()->getById($user_id);
      
        $dlangid="";
        foreach($dlangtextid as $lang){
        	$dlangid=$lang['id'];
        	
        }
        
      
        if($dlangid=="") {
        	
			 $this->getDeathUserLanguageTextTable()->savedeathuserforlang($langt['langtext'],$user_id);
		}else {
			$this->getDeathUserLanguageTextTable()->updatedeathuserforlang($langt['langtext'],$dlangid);		
		}	
	}else{
		
		$userInfo = $this->getDeathUserTable()->getUser($token);

    if(!empty($userInfo))
		{
		$id = $userInfo->deathuser_id;
		$submit = $userInfo->submit;
		$user = $request->getPost('user');
		//print_r($user);exit;
		$langt = $request->getPost('lang');
		//print_r($lang);exit;
        $update = $this->getDeathUserTable()->updateDeathUser($user,$id);

        $dlangtextid= $this->getDeathUserLanguageTextTable()->getById($id);
      
        $dlangid="";
        foreach($dlangtextid as $lang){
        	$dlangid=$lang['id'];
        	
        }
        //echo $dlangid;exit;
      
        if($dlangid=="") {
        	
			 $this->getDeathUserLanguageTextTable()->savedeathuserforlang($langt['langtext'],$id);
		}else {
			//print_r($langt['langtext']);exit;
			$this->getDeathUserLanguageTextTable()->updatedeathuserforlang($langt['langtext'],$dlangid);		
		
		}	
	if($submit=='draft')
	     {
			$this->getDeathUserTable()->updateSubmit($id);
			}
		}
	}
      $user = $request->getPost('user');
		$user_idd = $this->getDeathUserTable()->updateDeathUser($user,$user_id);
		//$DeathUser = $this->getDeathUserTable()->getDeathUserDetails($death_user_id);
		$link = $this->url()->fromRoute('add-death-user',array(),array('force_canonical' => true)).'?step='.$token.'&pos=2'; 
	if($user['submit']=='draft')
		{
		return $this->redirect()->toUrl('add-death-user');
		}
	else{
		return $this->redirect()->toUrl($link);
		}
    } 
	
	$user_session = new Container('user');
	if(empty($token)){
	$user_session->getManager()->getStorage()->clear('user'); 
	}
	$session_user_id =$user_session->user_id;
	
	$draft_data = $this->getDeathUserTable()->getDeathUserDetails($session_user_id);

    $result_thmb = $this->getImageUploadTable()->getById($session_user_id);
    $result_thmb2 = $this->getImageUploadTable()->getById($session_user_id);
    $DeathUsers = $this->getDeathUserTable()->fetchUser($current_user_id);
	$events = $this->getEventMapper()->findEvents($session_user_id, 'obituary');
	//print_r($events);exit;
	  $dates = array();

      

        foreach ($events as $event) {           

            $date = date('Y ,n ,j', strtotime($event['edate']));            

            $dates[$date] = $event['title'];

        }

      //print_r($dates);exit;

        $alerts = array();

        foreach ($events as $event) {

            $date = strtotime($event['start']);

          if($date > time() + 86400) {$alerts[$event['start']] = $event['title'];}

            

        }
        //echo "dsf";exit;
        $dlangtext= $this->getDeathUserLanguageTextTable()->getById($session_user_id);
      
        $dlang=array();
        foreach($dlangtext as $lang){
        	$dlang[]=$lang['langtext'];
        	
        }
       // print_r($dlang);exit;
        //print_r($langtext);exit;
   $sharedTasks = $this->getSharedMapper()->getSharedDocument('deathuser', $session_user_id, $current_user_id);
	//print_r($session_user_id." ". $current_user_id);exit;
    //echo $session_user_id;
   // print_r($DeathUsers);exit;
    
	// edu session
	$user_session = new container('edu');
	if(empty($token)){
	$user_session->getManager()->getStorage()->clear('edu'); 
	}
	$edu_session_id = $user_session->edu_id;		
	$edu_fetch = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($edu_session_id);
    
	$guset_session = new Container('guest');
	if(empty($token)){
	$guset_session->getManager()->getStorage()->clear('guest'); 
	}
	$gusetSession_id =  $guset_session->guset_id ;
	$guest = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($gusetSession_id);
	//print_r($guest);exit;
	$tribute_user_session = new container('tributes');
	if(empty($token)){
	$tribute_user_session->getManager()->getStorage()->clear('tributes'); 
	}
	// $tribute_user_session->tribute_id = $userInfo['deathuser_id'] ;
	$tributeSession = $tribute_user_session->tribute_id;
	
	$tribute_all_value = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($tributeSession); 
	//print_r($tribute_all_value);exit;
	/* foreach($tribute_all_value as $tribute_all_value1){
	
	  print "<pre>";
	print_r($tribute_all_value1);die;
	  
	  } */
	
	
	$femily_user_session = new container('femily');
	if(empty($token)){
	$femily_user_session->getManager()->getStorage()->clear('femily'); 
	}
	$femily_user_id = $femily_user_session->femily_id;
    $fetchallvalue_femily = $this->getUserFamilyDetailsTable()->getfamily($femily_user_id); 
     $fetchallvalue_femily2 = $this->getUserFamilyDetailsTable()->getfamilyPre($femily_user_id); 
//print_r($fetchallvalue_femily2);exit;
	
	$employer_session = new Container('employer');
	if(empty($token)){
	$employer_session->getManager()->getStorage()->clear('employer'); 
	}
	// $employer_session->employe_id =$userInfo['deathuser_id'];
	$EmployerSession_id =  $employer_session->employe_id ;
	$empl = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($EmployerSession_id);

	$professional_session = new Container('professional');
	if(empty($token)){
	$professional_session->getManager()->getStorage()->clear('professional'); 
	}
	// $professional_session->professional_id =$userInfo['deathuser_id'];
	$professionals_id =  $professional_session->professional_id ;
	$profess = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($professionals_id);
	
	$honor_session = new Container('honor');
	if(empty($token)){
	$honor_session->getManager()->getStorage()->clear('honor'); 
	}
	// $honor_session->honor_id =$userInfo['deathuser_id'];
	$honor_id =  $honor_session->honor_id ;
	$honor_value = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($honor_id);
	 
	 $digital_session = new Container('digital');
	 if(empty($token)){
	$digital_session->getManager()->getStorage()->clear('digital'); 
	}
	// $digital_session->digital_id =$userInfo['deathuser_id'];
	$digital_id =  $digital_session->digital_id ;
	$digital_value = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($digital_id);
	
	$privacy_user_session = new container('privacy');
	
	if(empty($token)){
	$privacy_user_session->getManager()->getStorage()->clear('privacy'); 
	}
	$privacySession = $privacy_user_session->privacy_id;
	// $privacySession;
   $fetchallvalue_privacy = $this->getUserPrivacyTable()->getprivacy($privacySession); 
   //print_r($fetchallvalue_privacy);exit;

	 
	$this->layout('layout/custom');

	return  array('deathuserid'=>$session_user_id,'countriesNames'=>$countriesNames,'causeNames'=>$causeNames,'langtext'=>$dlang,'sharedTasks' => $sharedTasks,'events' => $events, 'dates' => json_encode($dates),'spmention'=>$spmention,'edu_sesin_arr'=>$edu_fetch,'fetch_all_privacy'=>$fetchallvalue_privacy,'fetch_all_data2'=>$fetchallvalue_femily2,'fetch_all_data'=>$fetchallvalue_femily,'deathUsers'=>$DeathUsers,'result_thmb2'=>$result_thmb2,'result_thmb'=>$result_thmb,'draft_data'=>$draft_data,'edu_data'=>$edu_ses1,'guest_value'=>$guest,'tributeval'=>$tribute_all_value,'emp_all'=>$empl,'all_pro'=>$profess,'honorall'=>$honor_value,'digitalAll'=>$digital_value,'schoolall'=>$getschoolname,'univeristyall'=>$university_name1,'collegealltable'=>$college_name1,'allother'=>$othertable,'orbitory'=>$this->orbitory,'orbitory1'=>$obisuray_name);
	
    }
     public function getSharedMapper() {

        if (!$this->sharedTaskMapper) {

            $sm = $this->getServiceLocator();

            $this->sharedTaskMapper = $sm->get('SharedTasks\Model\SharedTask');

        }

        return $this->sharedTaskMapper;

    }
   public function getEventMapper() {

        if (!$this->eventMapper instanceof EventsInterface) {



            $this->setEventMapper($this->getServiceLocator()->get('EventsMapper'));

        }



        return $this->eventMapper;

    }
	 public function setEventMapper(EventsInterface $mapper) {

        $this->eventMapper = $mapper;



        return $this;

    }
   public function deleteAction()
   { 
       if ($this->zfcUserAuthentication()->hasIdentity()) {
             $current_user_id=$this->zfcUserAuthentication()->getIdentity()->getId();            
        }
        else 
           return $this->redirect()->toUrl('/user/login'); 
		
      $deathUser_id = $this->params()->fromQuery('id');
      $this->getDeathUserTable()->deleteDeathUser($deathUser_id,$current_user_id);
      $link = $this->url()->fromRoute('add-death-user');	
	  $this->layout('layout/custom');
	  return $this->redirect()->toUrl($link); 
		
   }
    public function ajax1Action(){
		
	}
      
    public function eduAction()
    {
		$token = $this->params()->fromQuery('step');
		if(empty($token)){
			if ($this->zfcUserAuthentication()->hasIdentity()) {
              $current_user_id=$this->zfcUserAuthentication()->getIdentity()->getId();   
              $link = $this->url()->fromRoute('add-death-user', array('force_canonical' => true)).'?error=Please save the Info first';
		  		  return $this->redirect()->toUrl($link);
        		}else{
        		  return $this->redirect()->toUrl('user/login');
        		} 
           //return $this->redirect()->toUrl('user/login');
        }
        $userInfo = $this->getDeathUserTable()->getUser($token);
		if(empty($userInfo)){
            //$this->flashMessenger()->addErrorMessage('Internal Server error raised please contact to site administration');
            return $this->redirect()->toRoute('home');   
        }
        
    $request =$this->getRequest();
if($request->isPost())
        {
	    $user = $request->getPost('user');
	    $user['deathuser_id']=$userInfo['deathuser_id'];
	    $ret = $this->getDeathUserMetaTable()->saveMeta($user,$userInfo['deathuser_id']);
	    $edu_session_id = $userInfo['deathuser_id'];
	    $user_session = new container('edu');
	    $user_session->edu_id = $edu_session_id;
		
	
		//$this->flashMessenger()->addSuccessMessage('Education saved Succssfully');
           //$link = $this->url()->fromRoute('application/default',array('controller' => 'index', 'action' => 'add-death-user'), array('force_canonical' => true)).'?step='.$token.'&pos=3';
	$link = $this->url()->fromRoute('add-death-user', array('force_canonical' => true)).'?step='.$token.'&pos=3';
		  
    return $this->redirect()->toUrl($link);
    }
		
	
    }
   
    public function familyAction()
    {        
     $token = $this->params()->fromQuery('step');
	  if(empty($token)){
	     if ($this->zfcUserAuthentication()->hasIdentity()) {
              $current_user_id=$this->zfcUserAuthentication()->getIdentity()->getId();   
              $link = $this->url()->fromRoute('add-death-user', array('force_canonical' => true)).'?error=Please save the Info first';
		  		  return $this->redirect()->toUrl($link);
        		}else{
        		  return $this->redirect()->toUrl('user/login');
        		}
     }
     $userInfo = $this->getDeathUserTable()->getUser($token);
     if(empty($userInfo)){
            return $this->redirect()->toRoute('add-death-user');   
        }
        $request =$this->getRequest();
		
    if($request->isPost())
    {
	
	$femily_user_session = new container('femily');
	$femily_user_id = $femily_user_session->femily_id;
	
	if($femily_user_id != $userInfo['deathuser_id']){
		 $userFamilyDetails = $request->getPost();  
		 $femily_user_session = new container('femily');
		 $femily_user_session->femily_id = $userInfo['deathuser_id'] ;	
		 $femily_user_id = $femily_user_session->femily_id;
		 //print_r($userFamilyDetails);exit;
		 $this->getUserFamilyDetailsTable()->insertFamilyDetails( $femily_user_id,$userFamilyDetails);
	}else{
	   $userFamilyDetails1 = $request->getPost(); 
	    //print_r($userFamilyDetails1);exit;
	   $this->getUserFamilyDetailsTable()->update($femily_user_id,$userFamilyDetails1); 
    } 	   
		
    $link = $this->url()->fromRoute('add-death-user',array(),array('force_canonical' => true)).'?step='.$token.'&pos=4';
    return $this->redirect()->toUrl($link);
    } 
	  $this->layout('layout/custom');	
    }
	
	
    public function privacyAction()
    {        

        $token = $this->params()->fromQuery('step'); 
        
		  if(empty($token)){
           if ($this->zfcUserAuthentication()->hasIdentity()) {
              $current_user_id=$this->zfcUserAuthentication()->getIdentity()->getId();   
              $link = $this->url()->fromRoute('add-death-user', array('force_canonical' => true)).'?error=Please save the Info first';
		  		  return $this->redirect()->toUrl($link);
        		}else{
        		  return $this->redirect()->toUrl('user/login');
        		}
        }
        $userInfo = $this->getDeathUserTable()->getUser($token);
        if(empty($userInfo)){
            //$this->flashMessenger()->addErrorMessage('Internal Server Error !');
            //return $this->redirect()->toRoute('home');   
            return $this->redirect()->toRoute('add-death-user');   
        }
        $request =$this->getRequest();
   if($request->isPost())
       {
	     $userPrivacyDetails = $request->getPost('user');
	    //print "<pre>";
		 //echo "hello";
		// print_r($userPrivacyDetails);die; 
	   
	    $privacy_user_session = new container('privacy');
	    $privacySession = $privacy_user_session->privacy_id;
	//echo $privacySession;exit;
	if($privacySession != $userInfo['deathuser_id'] ){
	 $userPrivacyDetails = $request->getPost();
	// print_r($userPrivacyDetails);die;
	 //print_r($this->getUserPrivacyTable());exit;
	// echo $userInfo['deathuser_id'];
	// print_r($userPrivacyDetails);
	$this->getUserPrivacyTable()->insertPrivacyDetails($userInfo['deathuser_id'],$userPrivacyDetails);
	
	 $privacy_user_session = new container('privacy');
	 $privacy_user_session->privacy_id = $userInfo['deathuser_id'] ;
	 $privacySession = $privacy_user_session->privacy_id;
	 	 
	 }else{
	     $userInfo_id = $userInfo->deathuser_id;
	     $userPrivacyDetails11 = $request->getPost();
		// print_r($userPrivacyDetails11);exit;
	   // print "<pre>";
		// print_r($userTributesDetails1->tribute1);die;
		$update_privacy[] = $userPrivacyDetails1->privacy2;
		$userInfo = $this->getUserPrivacyTable()->updateDetails($userInfo_id,$userPrivacyDetails11);
	}
	   
				
     $link = $this->url()->fromRoute('add-death-user',array(),array('force_canonical' => true)).'?step='.$token.'&pos=6';
           return $this->redirect()->toUrl($link);
    } 
		$this->layout('layout/custom');	
    }
   public function guestbookAction()
    {         
        $token = $this->params()->fromQuery('step'); 
        if(empty($token)){
            if ($this->zfcUserAuthentication()->hasIdentity()) {
              $current_user_id=$this->zfcUserAuthentication()->getIdentity()->getId();   
              $link = $this->url()->fromRoute('add-death-user', array('force_canonical' => true)).'?error=Please save the Info first';
		  		  return $this->redirect()->toUrl($link);
        		}else{
        		  return $this->redirect()->toUrl('user/login');
        		}
        }
        $userInfo = $this->getDeathUserTable()->getUser($token);
        if(empty($userInfo)){
            //$this->flashMessenger()->addErrorMessage('Internal Server Error !');
            //return $this->redirect()->toRoute('home');   
            return $this->redirect()->toRoute('add-death-user');   
        }
        $request =$this->getRequest();
        $dthid=$userInfo['deathuser_id'];
        if($request->isPost())
        {
           $user = $request->getPost('user');
          // print_r($user);exit;
           //$tmpemail=explode(",",$user['guest_email1']);
           $temp=explode(',',$user['guest_email1']);
           
          if(count($temp)>1){
           $this->getDeathUserMetaTable()->saveMeta($user,$userInfo['deathuser_id']);
           $guestbook=$this->getDeathUserGuestBookTable()->savedeathuserguestbook($user,$userInfo['deathuser_id']);
         }else {	
			  $this->getDeathUserMetaTable()->saveMeta($user,$userInfo['deathuser_id']);
		     $guestbook=$this->getDeathUserGuestBookTable()->savedeathuserguestbook($user,$userInfo['deathuser_id']);
			}		     
		   for($i=0;$i<count($temp);$i++){
			$to = $temp[$i];
         $subject = ucfirst($userInfo['first_name']) . "shared a guest book";
         $html = '<table><tr><td>please <a href="http://casite-693157.cloudaccess.net/antimnew/public/view-account?id='.$dthid.'&email='.$to.'">click here</a> to see the guestbook.</td></tr>';
  			//$html .= '<tr><td><a href="">Click here</a></td></table>';
//echo $html;exit;        
         $message = $html;
         $header = "From:rajeevbhatia2@gmail.com \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         mail ($to,$subject,$message,$header);		     	
	      }
			$guset_session = new Container('guest');
		   $guset_session->guset_id = $userInfo['deathuser_id'];
		   
		   $link = $this->url()->fromRoute('add-death-user',array(),array('force_canonical' => true)).'?step='.$token.'&pos=5';
           return $this->redirect()->toUrl($link);
        } 
		$this->layout('layout/custom');	
    }
	
	public function tributesAction()
    {        
        $token = $this->params()->fromQuery('step'); 
		//print_r($token); exit;
		if(empty($token)){
            if ($this->zfcUserAuthentication()->hasIdentity()) {
              $current_user_id=$this->zfcUserAuthentication()->getIdentity()->getId();   
              $link = $this->url()->fromRoute('add-death-user', array('force_canonical' => true)).'?error=Please save the Info first';
		  		  return $this->redirect()->toUrl($link);
        		}else{
        		  return $this->redirect()->toUrl('user/login');
        		}  
        }
        $userInfo = $this->getDeathUserTable()->getUser($token);
		//print_r($token); exit;
        if(empty($userInfo)){
            //$this->flashMessenger()->addErrorMessage('Internal Server Error !');
            //return $this->redirect()->toRoute('home');   
            return $this->redirect()->toRoute('add-death-user');   
        }
        $request =$this->getRequest();
   if($request->isPost())
       {
		   
	    //$userTributesDetails = $request->getPost('user');
		//print_r($userTributesDetails); exit;
	    //$tribute_user_session = new container('tributes');
	    //$tributeSession = $tribute_user_session->tribute_id;

	   $userTributesDetails = $request->getPost('user');
	   //print_r($userTributesDetails); exit;
		$dthid=$userInfo['deathuser_id'];
		 $temp=explode(',',$userTributesDetails['tribute_email']);
          if(count($temp)>1){
           $this->getDeathUserMetaTable()->saveMeta($userTributesDetails,$userInfo['deathuser_id']);
         }else {	
			  $this->getDeathUserMetaTable()->saveMeta($userTributesDetails,$userInfo['deathuser_id']);
		     $guestbook=$this->getDeathUserTributeTable()->savedeathusertribute($userTributesDetails,$userInfo['deathuser_id']);
			}
	 for($i=0;$i<count($temp);$i++){
			$to = $temp[$i];
         $subject = ucfirst($userInfo['first_name']) . "shared a tribute";
         $html = '<table><tr><td>please <a href="http://casite-693157.cloudaccess.net/antimnew/public/view-account?id='.$dthid.'&email='.$to.'">click here</a> to see the tribute.</td></tr>';
         $message = $html;
         $header = "From:rajeevbhatia2@gmail.com \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         mail ($to,$subject,$message,$header);		     	
	      }
	 $tribute_user_session = new container('tributes');
	 $tribute_user_session->tribute_id = $userInfo['deathuser_id'] ;
	 $tributeSession = $tribute_user_session->tribute_id;
		 
	 
	   
				
     $link = $this->url()->fromRoute('add-death-user',array(),array('force_canonical' => true)).'?step='.$token.'&pos=6';
           return $this->redirect()->toUrl($link);
    } 
		$this->layout('layout/custom');	
    }
	
 public function donateAction()
    {         
        $token = $this->params()->fromQuery('step'); 
       
        if(empty($token)){
            if ($this->zfcUserAuthentication()->hasIdentity()) {
              $current_user_id=$this->zfcUserAuthentication()->getIdentity()->getId();   
              $link = $this->url()->fromRoute('add-death-user', array('force_canonical' => true)).'?error=Please save the Info first';
		  		  return $this->redirect()->toUrl($link);
        		}else{
        		  return $this->redirect()->toUrl('user/login');
        		}
        }
        $userInfo = $this->getDeathUserTable()->getUser($token);
        if(empty($userInfo)){
            //$this->flashMessenger()->addErrorMessage('Internal Server Error !');
            //return $this->redirect()->toRoute('home');   
            return $this->redirect()->toRoute('add-death-user');   
        }
        $request =$this->getRequest();
        if($request->isPost())
        {
        	
           $user = $request->getPost('user');
          // print_r($user);exit;
           //$tmpemail=explode(",",$user['guest_email1']);
          //print_r($user);exit;
		  //echo $userInfo['deathuser_id']
		   $this->getDeathUserDonateTable()->saveDonate($user,$userInfo['deathuser_id']);
			$donate_session = new Container('donate');
		   $donate_session->donate_id = $userInfo['deathuser_id'];
		
		   $link = $this->url()->fromRoute('add-death-user',array(),array('force_canonical' => true)).'?step='.$token.'&pos=6';
           return $this->redirect()->toUrl($link);
        } 
		$this->layout('layout/custom');	
    }   
	
public function employementAction(){
	$token = $this->params()->fromQuery('step'); 
	if(empty($token)){
           if ($this->zfcUserAuthentication()->hasIdentity()) {
              $current_user_id=$this->zfcUserAuthentication()->getIdentity()->getId();   
              $link = $this->url()->fromRoute('add-death-user', array('force_canonical' => true)).'?error=Please save the Info first';
		  		  return $this->redirect()->toUrl($link);
        		}else{
        		  return $this->redirect()->toUrl('user/login');
        		}
        }
        $userInfo = $this->getDeathUserTable()->getUser($token);
	
	if(empty($userInfo)){
           // $this->flashMessenger()->addErrorMessage('Internal Server Error !');
            //return $this->redirect()->toRoute('home');   
            return $this->redirect()->toRoute('add-death-user');   
        }
        $request =$this->getRequest();
  if($request->isPost())
        {
		
		$user = $request->getPost('user');
		$this->getDeathUserMetaTable()->saveMeta($user,$userInfo['deathuser_id']);
		$employer_session = new Container('employer');
		$employer_session->employe_id =$userInfo['deathuser_id'];
		$EmployerSession_id =  $employer_session->employe_id ;
		$empl = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($EmployerSession_id);
		
	/*$this->flashMessenger()->addSuccessMessage('Guestbook information saved Succssfully');
           $link = $this->url()->fromRoute('application/default',array('controller' => 'index', 'action' => 'add-death-user'), array('force_canonical' => true)).'?step='.$token.'&pos=5';*/
	    $link = $this->url()->fromRoute('add-death-user',array(),array('force_canonical' => true)).'?step='.$token.'&pos=9';
           return $this->redirect()->toUrl($link);
        } 
		$this->layout('layout/custom');	
	}
	
	

	public function editAchivementAction(){
	 echo "hello12";
	
	}
	public function editPersionalAction(){
	
	echo "helloPPP";
	}
	public function editDigitalAction(){
	echo "helloDDD";
	}
	
	
	
	public function achiveAction(){
     
	$token = $this->params()->fromQuery('step'); 
	
        if(empty($token)){
            if ($this->zfcUserAuthentication()->hasIdentity()) {
              $current_user_id=$this->zfcUserAuthentication()->getIdentity()->getId();   
              $link = $this->url()->fromRoute('add-death-user', array('force_canonical' => true)).'?error=Please save the Info first';
		  		  return $this->redirect()->toUrl($link);
        		}else{
        		  return $this->redirect()->toUrl('user/login');
        		} 
        }
        $userInfo = $this->getDeathUserTable()->getUser($token);
	
	if(empty($userInfo)){
           // $this->flashMessenger()->addErrorMessage('Internal Server Error !');
            //return $this->redirect()->toRoute('home');   
            return $this->redirect()->toRoute('add-death-user');   
        }
        $request =$this->getRequest();
        if($request->isPost())
        {
		
	$user = $request->getPost('user');
	$this->getDeathUserMetaTable()->saveMeta($user,$userInfo['deathuser_id']);
	$professional_session = new Container('professional');
	$professional_session->professional_id =$userInfo['deathuser_id'];
	$professionals_id =  $professional_session->professional_id ;
	
	$profess = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($professionals_id);
	
	$link = $this->url()->fromRoute('add-death-user',array(),array('force_canonical' => true)).'?step='.$token.'&pos=10';
           return $this->redirect()->toUrl($link);
        } 
		$this->layout('layout/custom');	
	 }
	 
	 
public function honorsAction(){
	
	$token = $this->params()->fromQuery('step'); 
	
    if(empty($token)){
            if ($this->zfcUserAuthentication()->hasIdentity()) {
              $current_user_id=$this->zfcUserAuthentication()->getIdentity()->getId();   
              $link = $this->url()->fromRoute('add-death-user', array('force_canonical' => true)).'?error=Please save the Info first';
		  		  return $this->redirect()->toUrl($link);
        		}else{
        		  return $this->redirect()->toUrl('user/login');
        		} 
        }
        $userInfo = $this->getDeathUserTable()->getUser($token);
	
	if(empty($userInfo)){
        // $this->flashMessenger()->addErrorMessage('Internal Server Error !');
        //return $this->redirect()->toRoute('home');   
        return $this->redirect()->toRoute('add-death-user');   
        }
        $request =$this->getRequest();
    if($request->isPost())
    {
		
	$user = $request->getPost('user');
	$this->getDeathUserMetaTable()->saveMeta($user,$userInfo['deathuser_id']);
	$honor_session = new Container('honor');
	$honor_session->honor_id =$userInfo['deathuser_id'];
	$honor_id =  $honor_session->honor_id ;
	
	$honor_value = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($honor_id);
	$link = $this->url()->fromRoute('add-death-user',array(),array('force_canonical' => true)).'?step='.$token.'&pos=11';
           return $this->redirect()->toUrl($link);
        } 
		$this->layout('layout/custom');	
	}
	
	
public function digitalAction(){
	$token = $this->params()->fromQuery('step'); 
	if(empty($token)){
            if ($this->zfcUserAuthentication()->hasIdentity()) {
              $current_user_id=$this->zfcUserAuthentication()->getIdentity()->getId();   
              $link = $this->url()->fromRoute('add-death-user', array('force_canonical' => true)).'?error=Please save the Info first';
		  		  return $this->redirect()->toUrl($link);
        		}else{
        		  return $this->redirect()->toUrl('user/login');
        		}
        }
        $userInfo = $this->getDeathUserTable()->getUser($token);
	
	if(empty($userInfo)){
           // $this->flashMessenger()->addErrorMessage('Internal Server Error !');
            //return $this->redirect()->toRoute('home');   
            return $this->redirect()->toRoute('add-death-user');   
        }
        $request =$this->getRequest();
    if($request->isPost())
        {
	$user = $request->getPost('user');
	
	$this->getDeathUserMetaTable()->saveMeta($user,$userInfo['deathuser_id']);
	$digital_session = new Container('digital');
	$digital_session->digital_id =$userInfo['deathuser_id'];
	$digital_id1 =  $digital_session->digital_id ;
	
	$link = $this->url()->fromRoute('add-death-user',array(),array('force_canonical' => true)).'?step='.$token.'&pos=1';
           return $this->redirect()->toUrl($link);
        } 
		$this->layout('layout/custom');	
	
	 }
	public function viewObituaryAction()
    {
      $obituary_id = $this->params()->fromQuery('id');
	   if(!$obituary_id){
			return $this->redirect()->toRoute('view-obituary');
		}
		
		 $obituary=$this->getSearchObituaryTable()->getObituaryfetch($obituary_id);
		// print_r($obituary);exit;
       $this->layout('layout/custom');
	   return array('obituary'=>$obituary);
    }
    public function viewMemorialAction()
    {
      $memorial_id = $this->params()->fromQuery('id');
	   if(!$memorial_id){
			return $this->redirect()->toRoute('view-memorial');
		}
		
		 $memorial=$this->getSearchObituaryTable()->getMemoralizefetch($memorial_id);
		// print_r($obituary);exit;
       $this->layout('layout/custom');
	   return array('memorial'=>$memorial);
    }
    public function viewAccountAction()
    {
      $death_user_id = $this->params()->fromQuery('id');
      $guestemail = $this->params()->fromQuery('email');
      //echo $guestemail;exit;
      if(!$death_user_id){
			return $this->redirect()->toRoute('add-death-user');
		}
		//echo "sridhar";exit;
		$current_user_id="";
		if ($this->zfcUserAuthentication()->hasIdentity()) {
		$current_user_id=$this->zfcUserAuthentication()->getIdentity()->getId();
	}
		$DeathUsers = $this->getDeathUserTable()->fetchUser($current_user_id);
      $DeathUser = $this->getDeathUserTable()->getDeathUserDetails($death_user_id);
      $DeathUsermeta = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($death_user_id);
      $DeathUsertributes = $this->getUserTributesTable()->gettributes($death_user_id);
	  $getfamily = $this->getUserFamilyDetailsTable()->getfamily($death_user_id);
	  $getfamilyPre = $this->getUserFamilyDetailsTable()->getfamilyPre($death_user_id);
	  $usstates=$this->getSearchObituaryTable()->getLanguages();
	  if($guestemail!="") {
			$guestbook=$this->getDeathUserGuestBookTable()->getByIdEmail($death_user_id,$guestemail);
	  }else {
			$guestbook=$this->getDeathUserGuestBookTable()->getById($death_user_id);
	  }
	 
	  $guestbookforcnt=$this->getDeathUserGuestBookTable()->getById($death_user_id);
	  if($guestemail!="") {
			$tribute=$this->getDeathUserTributeTable()->getByIdEmail($death_user_id,$guestemail);	
	  }else{
	  		$tribute=$this->getDeathUserTributeTable()->getById($death_user_id);
	  }
	 
	  $getcandlecount=$this->getDeathUserGuestBookTable()->getcandlecount($death_user_id);
	  
	  $getdovecount=$this->getDeathUserGuestBookTable()->getdovecount($death_user_id);
	  $gettreecount=$this->getDeathUserGuestBookTable()->gettreecount($death_user_id);
	  $getnamaskarcount=$this->getDeathUserGuestBookTable()->getnamaskarcount($death_user_id);
	  $getdeadcount=$this->getDeathUserGuestBookTable()->getdeadcount($death_user_id);
	  $getdiyacount=$this->getDeathUserGuestBookTable()->getdiyacount($death_user_id);
	  $getwreathcount=$this->getDeathUserGuestBookTable()->getwreathcount($death_user_id);
	  $getthalicount=$this->getDeathUserGuestBookTable()->getthalicount($death_user_id);
	  $getomcount=$this->getDeathUserGuestBookTable()->getomcount($death_user_id);
	  $getcandlescount=$this->getDeathUserGuestBookTable()->getcandlescount($death_user_id);
$gethavankundcount=$this->getDeathUserGuestBookTable()->gethavankundcount($death_user_id);
$getashokachakracount=$this->getDeathUserGuestBookTable()->getashokachakracount($death_user_id);
//echo "sdf ".$getashokachakracount;exit;
	  $imageresults = $this->getImageUploadTable()->getById($death_user_id);
	  $imagesthumb = $this->getImageUploadTable()->getById($death_user_id);
	   $candlecnt=0;
	   $diyacnt=0;
	   $namasthecnt=0;
	   $omcnt=0;
	   $thalicnt=0;
	   $wreathcnt=0;
	   $dovecnt=0;
	   $dayofthedeathcnt=0;
	   $tulsicnt=0;
	   $flowcandlecnt=0;
	   foreach($guestbookforcnt as $gb){
	   	if($gb['offer_type']=='candle'){
		   	$candlecnt++;
	   	}
	   	if($gb['offer_type']=='diya'){
		   	$diyacnt++;
	   	}
	   	if($gb['offer_type']=='namasthe'){
		   	$namasthecnt++;
	   	}
	   	if($gb['offer_type']=='om'){
		   	$omcnt++;
	   	}
	   	if($gb['offer_type']=='thali'){
		   	$thalicnt++;
	   	}
	   	if($gb['offer_type']=='wreath'){
		   	$wreathcnt++;
	   	}
	   	if($gb['offer_type']=='dove'){
		   	$dovecnt++;
	   	}
	   	if($gb['offer_type']=='dayofthedeath'){
		   	$dayofthedeathcnt++;
	   	}
	   	if($gb['offer_type']=='tulsi'){
		   	$tulsicnt++;
	   	}
	   	if($gb['offer_type']=='flowcandle'){
		   	$flowcandlecnt++;
	   	}
     }
    
		$results 		= $this->getUploadTable()->fetchFromAdmin();
		$results_thmb 	= $this->getUploadTable()->fetchFromAdmin();
		
		
		
       $this->layout('layout/custom');
	   return array('deathuserid'=>$death_user_id,'deathUsers'=>$DeathUsers,'deathUser'=>$DeathUser,'candle'=>$candlecnt,
	   'diya'=>$diyacnt,'namasthe'=>$namasthecnt,'om'=>$omcnt,'thali'=>$thalicnt,'wreath'=>$wreathcnt,
	   'dove'=>$dovecnt,'dayofthedeath'=>$dayofthedeathcnt,'tulsi'=>$tulsicnt,'flowcandle'=>$flowcandlecnt,
	   'guestemail'=>$guestemail,'tribute'=>$tribute,'guestbook'=>$guestbook,'deathUsermeta'=>$DeathUsermeta,
	   'deathUsertributes'=>$DeathUsertributes,'getfamily'=>$getfamily,'getfamilyPre'=>$getfamilyPre,'message' => $message,
	   'results'=>$results,'results_thmb'=>$results_thmb,'getcandlecount'=>$getcandlecount,'gettreecount'=>$gettreecount,
	   'getomcount'=>$getomcount,'getcandlescount'=>$getcandlescount,'getnamaskarcount'=>$getnamaskarcount,'getwreathcount'=>$getwreathcount,
	   'getthalicount'=>$getthalicount,'getdiyacount'=>$getdiyacount,'getdovecount'=>$getdovecount,'getdeadcount'=>$getdeadcount,
	   'gethavankundcount'=>$gethavankundcount,'getashokachakracount'=>$getashokachakracount,'imagesres'=>$imageresults,'imagesthumb'=>$imagesthumb);
    }
    public function sendguestofferAction()
    {
      $death_user_id = $this->params()->fromQuery('id');
      
		 $request =$this->getRequest();
		if($request->isPost())
      {
			$data = $request->getPost('user');
			//print_r($data);exit;
			$guestbook=$this->getDeathUserGuestBookTable()->savedeathuserguestbook($data,$death_user_id);
		}
      $link = $this->url()->fromRoute('view-account',array('force_canonical' => true)).'?id='.$death_user_id.'&email='.$data['guest_email1']; 
		return $this->redirect()->toUrl($link);
   }
    public function sendtributeofferAction()
    {
      $death_user_id = $this->params()->fromQuery('id');
      if(!$death_user_id){
			return $this->redirect()->toRoute('add-death-user');
		}
		$request =$this->getRequest();
		if($request->isPost())
      {
			$data = $request->getPost('user');
			//print_r($data); exit;
			$guestbook=$this->getDeathUserTributeTable()->savedeathusertribute($data,$death_user_id);
		}
      $link = $this->url()->fromRoute('view-account',array('force_canonical' => true)).'?id='.$death_user_id.'&email='.$data['tribute_email']; 
		return $this->redirect()->toUrl($link);
   }
	public function moreAction()
	{
		$this->layout('layout/custom');
	}
	
	public function editProfileAction()
	{
	  // echo "hello";
	  // die;
	   
	  $obisuary_session = new Container('obituaries');
      
      $death_user_id = $this->params()->fromQuery('id');
      $obisuary_session->orbitory1=$death_user_id;
    //echo $obisuray_name1;exit;
	  $deathUser = $this->getDeathUserTable()->getDeathUserDetails($death_user_id);
	  $edu_result = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($death_user_id);
	
	  $getschoolname1 = $this->getSchoolTable()->school_name(); 
	  $college_name1 = $this->getCollegeTable()->college_name_all();
	  $university_name = $this->getUniversityTable()->university_name_all();
	  $other_school = $this->getOtherTable()->other_name_all();	
		
	  $DeathUsertributes = $this->getUserTributesTable()->gettributes($death_user_id);
	  $getfamily = $this->getUserFamilyDetailsTable()->getfamily($death_user_id);
	  $getfamilyPre = $this->getUserFamilyDetailsTable()->getfamilyPre($death_user_id);
	  $causeNames = $this->getCauseofDeathTable()->getCauseofDeathNames();
		
      $fetchallvalue_femily = $this->getUserFamilyDetailsTable()->getfamily($death_user_id); 
      $fetchallvalue_femily2 = $this->getUserFamilyDetailsTable()->getfamilyPre($death_user_id); 
	  //print_r($edu_result); exit;
		//print_r($getfamilyPre); exit;
	
	  // foreach($DeathUsertributes as $DeathUsertributes1){
	   
	        // $DeathUsertributes11[] = $DeathUsertributes1;
	   
	        // }
	
	    // print "<pre>";
		// echo "hello";
		// print_r($DeathUsertributes11);die;
	
	
	if($this->getRequest()->isPost())
		{	 
		
		 $data = array();
		 $user = $this->getRequest()->getPost();
		
		
		// Start Process :: update Info_tab
		$death_user_table = $user->user;
		if($death_user_table!=""){
			$update = $this->getDeathUserTable()->updateDeathUser($death_user_table,$death_user_id);
		}
	
		 //print "<pre>";
		//print_r($death_user_table);die;
		
		$edu_details = $user->edu;
		if($edu_details!=""){
			$result =$this->getDeathUserMetaTable()->saveMeta($edu_details,$death_user_id);
		}
		        // Update Family_tab details
				$userFamilyDetails = array();
				$userFamilyDetails = $user->family;
				//print_r($userFamilyDetails); exit;
				$details = array_filter($userFamilyDetails);
				if(!empty($details))
				{
				if(!empty($getfamily))
				{
					$res_fam = $this->getUserFamilyDetailsTable()->update($death_user_id,$details);
				}
			else{
				echo "sri";exit;
					$res_fam = $this->getUserFamilyDetailsTable()->insertFamilyDetails($death_user_id,$details);
				}
				}
				//  End Process :: update Family_tab data
				
				
			//  Start Process :: Update Guestbook_tab details
				$guestbook_details = $user->guestbook;
			if($guestbook_details!=""){
				$res = $this->getDeathUserMetaTable()->saveMeta($guestbook_details,$death_user_id);
			}
				
			//  End Process :: update Guestbook_tab data
			
			//work 
			//  Start Process :: Update tribute_tab details
			$tribute_data = array();
			$tribute_data[] = $user->tribute1;
			//echo print_r($tribute_data);exit;
			
			// $tribute_data[] = $user->tribute2;
			if($tribute_data[0]!=""){
			if(!empty($DeathUsertributes))
			{
			 $resu = $this->getUserTributesTable()->updateDetails($death_user_id,$tribute_data);
			}
			else{
			$resu = $this->getUserTributesTable()->insertTributesDetails($tribute_data,$death_user_id);
			}
			}
			
			
			//  End Process :: update tribute_tab data
			
			
				
			
        $link = $this->url()->fromRoute('edit-profile',array('force_canonical' => true)).'?id='.$death_user_id; 
	
		return $this->redirect()->toUrl($link);
			
				
				
		}
		$results 		= $this->getUploadTable()->fetchFromAdmin();
		$results_thmb 	= $this->getUploadTable()->fetchFromAdmin();
		
		$this->layout('layout/custom');
	
    return array('fetch_all_value'=>$getschoolname1,'college_name'=>$college_name1,
    'university_name1'=>$university_name,'other_school_name'=>$other_school,
    'death_user'=>$deathUser,'edu_result'=>$edu_result,'user_tribute'=>$DeathUsertributes,'getfamily'=>$getfamily,'getfamilyPre'=>$getfamilyPre,'results'=>$results,'edit_obisurie'=>$obisuray_name1,'results_thmb'=>$results_thmb,'causeNames'=>$causeNames,'fetch_all_data2'=>$fetchallvalue_femily2,'fetch_all_data'=>$fetchallvalue_femily); 	 
	}
public function editdeathuserAction(){
	//echo "sr";exit;
	$obisuary_session = new Container('obituaries');
      $obisuray_name = $obisuary_session->orbitory1;
		$spmention=$this->getSearchObituaryTable()->getSpmentionfetch();
	   $getschoolname = $this->getSchoolTable()->school_name(); 
		$college_name1 = $this->getCollegeTable()->college_name_all();
		$university_name1 = $this->getUniversityTable()->university_name_all();
		$othertable = $this->getOtherTable()->other_name_all();
		$causeNames = $this->getCauseofDeathTable()->getCauseofDeathNames();
		$countriesNames = $this->getCountryofDeathTable()->getCountryofDeathNames();
	    // print "<pre>";
	    // print_r($othertable);die;
	if ($this->zfcUserAuthentication()->hasIdentity()) {
    $current_user_id=$this->zfcUserAuthentication()->getIdentity()->getId();
    //echo $current_user_id;exit;
    }
   /* else 
    return $this->redirect()->toUrl('user/login');*/ 
    $token = $this->params()->fromQuery('step');
	if(!empty($token)){
    $userInfo = $this->getDeathUserTable()->getUser($token);
$deathid=$userInfo['deathuser_id'];
//echo $deathid;exit;
    if(empty($userInfo)){
    //$this->flashMessenger()->addErrorMessage('Internal Server error raised please contact to site administration');

    return $this->redirect()->toRoute('home');   
  
    }
}
  
    $request =$this->getRequest();
    if($request->isPost())
    {
    	   
	$user = $request->getPost('user');
	
	//print_r($user);die;
	if(empty($token))
		{
			if ($this->zfcUserAuthentication()->hasIdentity()) {
    $current_user_id=$this->zfcUserAuthentication()->getIdentity()->getId();
    //echo $current_user_id;exit;
    }
    else 
    return $this->redirect()->toUrl('user/login');
      $user = $request->getPost('user');
    	$langt = $request->getPost('lang');
		$token = $this->createToken();
		$user['steptoken'] =$token;
        $user['user_id'] =$current_user_id;
		$user['obituary'] = $obisuray_name;
		$draft = $request->getPost('draft_info');
		
		if($draft == 'draft_info')
		{
			$user['submit'] = 'draft';	
		}
		else
		{
			$user['submit'] = 'save';
		}
	
     $user_id = $this->getDeathUserTable()->saveDeathUser($user);
	 $user_session = new Container('user');
	 //print_r($user_session);exit;
	 $user_session->user_id =$user_id;
	 $dlangtextid= $this->getDeathUserLanguageTextTable()->getById($user_id);
      
        $dlangid="";
        foreach($dlangtextid as $lang){
        	$dlangid=$lang['id'];
        	
        }
        
      
        if($dlangid=="") {
        	
			 $this->getDeathUserLanguageTextTable()->savedeathuserforlang($langt['langtext'],$user_id);
		}else {
			$this->getDeathUserLanguageTextTable()->updatedeathuserforlang($langt['langtext'],$dlangid);		
		}	
	}else{
		
		$userInfo = $this->getDeathUserTable()->getUser($token);

    if(!empty($userInfo))
		{
		$id = $userInfo->deathuser_id;
		$submit = $userInfo->submit;
		$user = $request->getPost('user');
		//print_r($user);exit;
		$langt = $request->getPost('lang');
		//print_r($lang);exit;
        $update = $this->getDeathUserTable()->updateDeathUser($user,$id);

        $dlangtextid= $this->getDeathUserLanguageTextTable()->getById($id);
      
        $dlangid="";
        foreach($dlangtextid as $lang){
        	$dlangid=$lang['id'];
        	
        }
        //echo $dlangid;exit;
      
        if($dlangid=="") {
        	
			 $this->getDeathUserLanguageTextTable()->savedeathuserforlang($langt['langtext'],$id);
		}else {
			//print_r($langt['langtext']);exit;
			$this->getDeathUserLanguageTextTable()->updatedeathuserforlang($langt['langtext'],$dlangid);		
		
		}	
	if($submit=='draft')
	     {
			$this->getDeathUserTable()->updateSubmit($id);
			}
		}
	}
      $user = $request->getPost('user');
		$user_idd = $this->getDeathUserTable()->updateDeathUser($user,$user_id);
		$link = $this->url()->fromRoute('add-death-user',array(),array('force_canonical' => true)).'?step='.$token.'&pos=2'; 
	if($user['submit']=='draft')
		{
		return $this->redirect()->toUrl('add-death-user');
		}
	else{
		return $this->redirect()->toUrl($link);
		}
    } 
	//echo "ddd";
	$user_session = new Container('user');
	//if(empty($token)){
	$user_session->getManager()->getStorage()->clear('user'); 
	//}
	if($user_session->user_id==""){
		//$user_session = new Container('user');
		$user_session->user_id =$deathid;
	}
	$session_user_id =$user_session->user_id;
	//$deathid
    $result_thmb = $this->getImageUploadTable()->getById($session_user_id);
    $result_thmb2 = $this->getImageUploadTable()->getById($session_user_id);
    $imageresults = $this->getImageUploadTable()->getById($deathid);
	  $imagesthumb = $this->getImageUploadTable()->getById($deathid);
    $DeathUsers = $this->getDeathUserTable()->fetchUser($current_user_id);
$DeathUser = $this->getDeathUserTable()->getDeathUserDetails($deathid);
	$events = $this->getEventMapper()->findEvents($session_user_id, 'obituary');
	//print_r($events);exit;
	  $dates = array();

      

        foreach ($events as $event) {           

            $date = date('Y ,n ,j', strtotime($event['edate']));            

            $dates[$date] = $event['title'];

        }

      //print_r($dates);exit;

        $alerts = array();

        foreach ($events as $event) {

            $date = strtotime($event['start']);

          if($date > time() + 86400) {$alerts[$event['start']] = $event['title'];}

            

        }
        //echo "dsf";exit;
        $dlangtext= $this->getDeathUserLanguageTextTable()->getById($session_user_id);
      
        $dlang=array();
        foreach($dlangtext as $lang){
        	$dlang[]=$lang['langtext'];
        	
        }
       // print_r($dlang);exit;
        //print_r($langtext);exit;
   $sharedTasks = $this->getSharedMapper()->getSharedDocument('deathuser', $session_user_id, $current_user_id);
	//print_r($session_user_id." ". $current_user_id);exit;
    //echo $session_user_id;
   // print_r($DeathUsers);exit;
    $draft_data = $this->getDeathUserTable()->getDeathUserDetails($session_user_id);
	// edu session
	$user_session = new container('edu');
	//if(empty($token)){
	$user_session->getManager()->getStorage()->clear('edu'); 
	//}
	if($user_session->edu_id==""){
		$user_session->edu_id =$deathid;
	}
	$edu_session_id = $user_session->edu_id;		
	$edu_fetch = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($edu_session_id);
    
	$guset_session = new Container('guest');
	//if(empty($token)){
	$guset_session->getManager()->getStorage()->clear('guest'); 
	//}
	if($guset_session->guset_id==""){
		$guset_session->guset_id =$deathid;
	}
	$gusetSession_id =  $guset_session->guset_id ;
	$guest = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($gusetSession_id);
	//print_r($guest);exit;
	$tribute_user_session = new container('tributes');
	//if(empty($token)){
	$tribute_user_session->getManager()->getStorage()->clear('tributes'); 
	//}
	if($tribute_user_session->tribute_id==""){
		$tribute_user_session->tribute_id =$deathid;
	}
	// $tribute_user_session->tribute_id = $userInfo['deathuser_id'] ;
	$tributeSession = $tribute_user_session->tribute_id;
	
	$tribute_all_value = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($tributeSession); 
	//print_r($tribute_all_value);exit;
	/* foreach($tribute_all_value as $tribute_all_value1){
	
	  print "<pre>";
	print_r($tribute_all_value1);die;
	  
	  } */
	
	
	$femily_user_session = new container('femily');
	//if(empty($token)){
	$femily_user_session->getManager()->getStorage()->clear('femily'); 
	//}
	if($femily_user_session->femily_id==""){
		$femily_user_session->femily_id =$deathid;
	}
	$femily_user_id = $femily_user_session->femily_id;
    $fetchallvalue_femily = $this->getUserFamilyDetailsTable()->getfamily($femily_user_id); 
     $fetchallvalue_femily2 = $this->getUserFamilyDetailsTable()->getfamilyPre($femily_user_id); 
//print_r($fetchallvalue_femily2);exit;
	
	$employer_session = new Container('employer');
	//if(empty($token)){
	$employer_session->getManager()->getStorage()->clear('employer'); 
	//}
	if($employer_session->employe_id==""){
		$employer_session->employe_id =$deathid;
	}
	// $employer_session->employe_id =$userInfo['deathuser_id'];
	$EmployerSession_id =  $employer_session->employe_id ;
	$empl = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($EmployerSession_id);

	$professional_session = new Container('professional');
	//if(empty($token)){
	$professional_session->getManager()->getStorage()->clear('professional'); 
	//}
	if($professional_session->professional_id==""){
		$professional_session->professional_id =$deathid;
	}
	// $professional_session->professional_id =$userInfo['deathuser_id'];
	$professionals_id =  $professional_session->professional_id ;
	$profess = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($professionals_id);
	
	$honor_session = new Container('honor');
	//if(empty($token)){
	$honor_session->getManager()->getStorage()->clear('honor'); 
	//}
	if($honor_session->honor_id==""){
		$honor_session->honor_id =$deathid;
	}
	// $honor_session->honor_id =$userInfo['deathuser_id'];
	$honor_id =  $honor_session->honor_id ;
	$honor_value = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($honor_id);
	 
	 $digital_session = new Container('digital');
	 //if(empty($token)){
	$digital_session->getManager()->getStorage()->clear('digital'); 
	//}
	if($digital_session->digital_id==""){
		$digital_session->digital_id =$deathid;
	}
	
	// $digital_session->digital_id =$userInfo['deathuser_id'];
	$digital_id =  $digital_session->digital_id ;
	$digital_value = $this->getDeathUserMetaTable()->getDeathUserMEtaDetails($digital_id);
	
$privacy_user_session = new container('privacy');
	
	//if(empty($token)){
	$privacy_user_session->getManager()->getStorage()->clear('privacy'); 
	//}
	if($privacy_user_session->privacy_id==""){
		$privacy_user_session->privacy_id =$deathid;
	}
	//echo $deathid;exit;
	$privacySession = $privacy_user_session->privacy_id;
	// $privacySession;
	//echo $privacySession;exit;
   $fetchallvalue_privacy = $this->getUserPrivacyTable()->getprivacy($privacySession);	
	//print_r($fetchallvalue_privacy);exit;
	$getcandlecount=$this->getDeathUserGuestBookTable()->getcandlecount($session_user_id);
	  
	  $getdovecount=$this->getDeathUserGuestBookTable()->getdovecount($session_user_id);
	  $gettreecount=$this->getDeathUserGuestBookTable()->gettreecount($session_user_id);
	  $getnamaskarcount=$this->getDeathUserGuestBookTable()->getnamaskarcount($session_user_id);
	  $getdeadcount=$this->getDeathUserGuestBookTable()->getdeadcount($session_user_id);
	  $getdiyacount=$this->getDeathUserGuestBookTable()->getdiyacount($session_user_id);
	  $getwreathcount=$this->getDeathUserGuestBookTable()->getwreathcount($session_user_id);
	  $getthalicount=$this->getDeathUserGuestBookTable()->getthalicount($session_user_id);
	  $getomcount=$this->getDeathUserGuestBookTable()->getomcount($session_user_id);
	  $getcandlescount=$this->getDeathUserGuestBookTable()->getcandlescount($session_user_id);
$gethavankundcount=$this->getDeathUserGuestBookTable()->gethavankundcount($session_user_id);
$getashokachakracount=$this->getDeathUserGuestBookTable()->getashokachakracount($session_user_id);
	
	$this->layout('layout/custom');

	return  array('getcandlecount'=>$getcandlecount,'gettreecount'=>$gettreecount,
	   'getomcount'=>$getomcount,'getcandlescount'=>$getcandlescount,'getnamaskarcount'=>$getnamaskarcount,'getwreathcount'=>$getwreathcount,
	   'getthalicount'=>$getthalicount,'getdiyacount'=>$getdiyacount,'getdovecount'=>$getdovecount,'getdeadcount'=>$getdeadcount,
	   'gethavankundcount'=>$gethavankundcount,'getashokachakracount'=>$getashokachakracount,'deathuserid'=>$session_user_id,'deathUser'=>$DeathUser,
	   'countriesNames'=>$countriesNames,'causeNames'=>$causeNames,'langtext'=>$dlang,'sharedTasks' => $sharedTasks,'events' => $events,
	   'dates' => json_encode($dates),'spmention'=>$spmention,'edu_sesin_arr'=>$edu_fetch,'fetch_all_data2'=>$fetchallvalue_femily2,
	   'fetch_all_data'=>$fetchallvalue_femily,'fetch_all_privacy'=>$fetchallvalue_privacy,'deathUsers'=>$DeathUsers,'imagesres'=>$imageresults,
	   'imagesthumb'=>$imagesthumb,'result_thmb2'=>$result_thmb2,'result_thmb'=>$result_thmb,'draft_data'=>$draft_data,'edu_data'=>$edu_ses1,'guest_value'=>$guest,
	   'tributeval'=>$tribute_all_value,'emp_all'=>$empl,'all_pro'=>$profess,'honorall'=>$honor_value,'digitalAll'=>$digital_value,'schoolall'=>$getschoolname,
	   'univeristyall'=>$university_name1,'collegealltable'=>$college_name1,'allother'=>$othertable,'orbitory'=>$this->orbitory,'orbitory1'=>$obisuray_name);
	
	}
	public function getguestbookAction()
    {
     $request = $this->getRequest();
		if ($request->isPost()) {
			 $data = $request->getPost();
			$guestbook=$this->getDeathUserGuestBookTable()->getdeathuserguestbook($data);
			echo json_encode($guestbook,JSON_FORCE_OBJECT);
		}
   
   }
   
   public function gettributeAction()
    {
     $request = $this->getRequest();
		if ($request->isPost()) {
			 $data = $request->getPost();
			$guestbook=$this->getDeathUserTributeTable()->getdeathusertribute($data);
			echo json_encode($guestbook,JSON_FORCE_OBJECT);
		}
   
   }
	
    public function encryptToken($user_id)
    {
        $filter = new \Zend\Filter\Encrypt(array('adapter' => 'BlockCipher')); 
        $filter->setKey('AntimSanskar');
        $encrypted = urlencode($filter->filter($user_id));
        return  $encrypted; 
    }
    public function decryptToken($query)
    {
        $filter = new \Zend\Filter\Decrypt();
        $filter->setKey('AntimSanskar');
        $user_id = (int)$filter->filter(urldecode($query)); 
        return $user_id;
    }
    
    /**
     * Get Random unique Token default length=16
     * 
     * @author developed by Trs Software Solutions
     * @return string
     **/
    private function createToken($length = 16)
    {
        $rand = Rand::getString($length,'abcdefghijklmnopqrstuvwxyz123456789', true);
        return $rand;
    }
	
	
	public function uploaddAction()
	{
	
	$user_session = new Container('user');
	 $obituary_id= $user_session->user_id;
		if (!$this->zfcUserAuthentication()->hasIdentity()) {            
          return $this->redirect()->toUrl('user/login');  
    }
   // echo $obituary_id;
		$current_user_id=$this->zfcUserAuthentication()->getIdentity()->getId();
		
		$user_name = $this->zfcUserAuthentication()->getIdentity()->getUsername(); 
		$results = $this->getImageUploadTable()->getById($obituary_id);	
		$result_for = $this->getImageUploadTable()->getById($obituary_id);
		//print_r($result_for);exit;
		$result_thmb = $this->getImageUploadTable()->getById($obituary_id);
		$result_thmb2 = $this->getImageUploadTable()->getById($obituary_id);
		//print_r($result_thmb);exit;
		$DeathUsers = $this->getDeathUserTable()->fetchUser($current_user_id);
		$draft_data = $this->getDeathUserTable()->getDeathUserDetails($obituary_id);
		//print_r($DeathUsers);exit;
		$this->layout('layout/custom');
		return array('draft_data'=>$draft_data,'results'=>$results,'deathuserid'=>$obituary_id,'result_for'=>$result_for,'result_thmb2'=>$result_thmb2,'result_thmb'=>$result_thmb,'user_name'=>$user_name,'deathUsers'=>$DeathUsers);
	}
	
	public function deleteFileAction()
	{
		if (!$this->zfcUserAuthentication()->hasIdentity())
		{
			return $this->redirect()->toUrl('home');	           
		}
		$id = (int) $this->params()->fromQuery('id',0);
	
		if($id==0 || empty($id))
		{
			echo 'You are Not authorized To perform this action';
		}
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId(); 
		//echo $user_id;exit;
		$results = $this->getImageUploadTable()->delete($id);
		if($results){
			//echo 'Deleted Successfully';
			return $this->redirect()->toUrl('uploadd');
		}
		else{
			//echo 'Not Deleted';
			return $this->redirect()->toUrl('uploadd');
		}
		
	}
	public function thirdAction()
	{
		// show only those result that uploaded by admin
		
		$results 		= $this->getUploadTable()->fetchFromAdmin();
		$results_thmb 	= $this->getUploadTable()->fetchFromAdmin();
		//print_r($results_thmb);exit;
		
		$this->layout('layout/custom');
		return array('results'=>$results,'results_thmb'=>$results_thmb);
	}
	//this one for candle
	
	public function candleAction()
	{
		// show only those result that uploaded by admin
		
		$results 		= $this->getUploadTable()->fetchFromAdmin();
		$results_thmb 	= $this->getUploadTable()->fetchFromAdmin();
		
		$this->layout('layout/custom');
		return array('results'=>$results,'results_thmb'=>$results_thmb);
	}
	
 // this one for diyas 
 
      public function diyasAction()
	{
		// show only those result that uploaded by admin
		
		$results 		= $this->getUploadTable()->fetchFromAdmin();
		$results_thmb 	= $this->getUploadTable()->fetchFromAdmin();
		
		$this->layout('layout/custom');
		return array('results'=>$results,'results_thmb'=>$results_thmb);
	}
	public function getDeathUserLanguageTextTable() {
   	
        if (!$this->deathUserLanguageTextTable) {
            $sm = $this->getServiceLocator();
            $this->deathUserLanguageTextTable = $sm->get('Application\Model\DeathUserLanguageTextTable');
        }
        return $this->deathUserLanguageTextTable;
    }
   public function getLanguageTextTable() {
   	
        if (!$this->languageTextTable) {
            $sm = $this->getServiceLocator();
            $this->languageTextTable = $sm->get('Application\Model\LanguageTextTable');
        }
        return $this->languageTextTable;
    }
     public function languagetextAction() {
		 $request = $this->getRequest();
		 if ($request->isPost()) {
			 $data = $request->getPost();
			 
    	 	 $ltext=$this->getLanguageTextTable()->getText($data['ltext']);
    	 	// print_r($ltext);exit;
    	 	 echo json_encode($ltext,JSON_FORCE_OBJECT);
    	}
    }
	 public function getFindTable() {
        if (!$this->findTable) {
            $sm = $this->getServiceLocator();
            $this->findTable = $sm->get('CremationPlan\Model\FindTable');
        }
        return $this->findTable;
    }
	public function styleAction()
	{
		//$results	= $this->getUploadTable()->fetchFromAdmin();
		//$results_thmb = $this->getUploadTable()->fetchFromAdmin();
		 $languages = $this->getFindTable()->getLanguages();
		 $languagesText = $this->getLanguageTextTable()->fetch();
		 $user_session = new Container('user');
	$session_user_id =$user_session->user_id;
	$deathuserid=$session_user_id;
		$request =$this->getRequest();
		
   if($request->isPost())
    {
    	//echo "<pre>";
		//print_r($languagesText);exit;
    	$dlang=array();
      $data = $request->getPost('lang');
      //print_r($data);exit;
      foreach($data as $d){
      	$tmp=explode('-',$d);
      	$dlang[$tmp[1]]=$tmp[0];
      }
   $user_session = new Container('user');
	$session_user_id =$user_session->user_id;
	$deathuserid=$session_user_id;
	$token = $this->params()->fromQuery('step');
	$languagText = $this->getLanguageTextTable()->getByIdType($dlang);
	$dlangtextid= $this->getDeathUserLanguageTextTable()->getById($session_user_id);
	$dlangid="";
        foreach($dlangtextid as $lang){
        	$dlangid=$lang['id'];
        	
        }
       
        if($dlangid=="") {
        	
			 $this->getDeathUserLanguageTextTable()->savedeathuserforlang($languagText,$session_user_id);
		}else {
			
			$this->getDeathUserLanguageTextTable()->updatedeathuserforlang($languagText,$dlangid);		
		}	
	//
	   // $insert_data = $this->getUploadTable()->insert_obituaries($data);
	   // $this->getDeathUserLanguageTextTable()->savedeathuserforlang($languagText,$session_user_id);
		$obisuary_session = new Container('obituaries');
      $obisuary_session->orbitory1 = $data;
		$obisuray_name = $obisuary_session->orbitory1;
		$link = $this->url()->fromRoute('add-death-user',array('force_canonical' => true)).'?step='.$token; 
	
		return $this->redirect()->toUrl($link);
		// print "<pre>";
		// echo "hello";
		// print_r($obisuray_name);die;
	// $this->orbituary = $data;
	}
		$result_thmb = $this->getImageUploadTable()->getById($session_user_id);
		$result_thmb2 = $this->getImageUploadTable()->getById($session_user_id);
		//print_r($session_user_id);exit;
		$draft_data = $this->getDeathUserTable()->getDeathUserDetails($session_user_id);
		$this->layout('layout/custom');
		return array('draft_data'=>$draft_data,'deathuserid'=>$deathuserid,'result_thmb2'=>$result_thmb2,'result_thmb'=>$result_thmb,'languages'=>$languages,'languagesText'=>$languagesText,'obituaries'=>$obisuray_name);
	}
		
		public function travelAction()
	{
			return new ViewModel();
			
		}
	
		public function travelajaxAction()
	{
			return new ViewModel();
			
		}

			public function groceriesAction()
	{
			return new ViewModel();
			
		}

				public function billingAction()
	{
			return new ViewModel();
			
		}

				public function functionsAction()
	{
			return new ViewModel();
			
		}

				public function getdataAction()
	{
			return new ViewModel();
			
		}

				public function shoppingcartAction()
	{
			return new ViewModel();
			
		}
	
	
	public function uploadAction()
	{
		//$obisuary_session = new Container('obituaries');
      //$obituray_id = $obisuary_session->orbitory1;
     	$user_session = new Container('user');
	 	$obituary_id= $user_session->user_id;
		if (!$this->zfcUserAuthentication()->hasIdentity())
		{
			return $this->redirect()->toUrl('user/login');         
		}
		if(!isset($obituary_id)) {
					return $this->redirect()->toRoute('add-death-user');
		}
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId(); 
		$results = $this->getImageUploadTable()->getById($obituary_id);	
		if(count($results<9))
		{
			if($this->getRequest()->isPost())
			{
				$dirName = 'module/Application/assets/images/'.$obituary_id;
				//echo $dirName;exit;
				if (!is_dir($dirName)) {
                   mkdir($dirName,0777,true);         
            }
				$uploads_dir ='module/Application/assets/images/'.$obituary_id;
				$tmp_name = $_FILES["file"]["tmp_name"];
				$name = uniqid()."".$_FILES["file"]["name"];
				$filename = $name;
				
				$path = $_FILES['file']['name'];
				$ext = pathinfo($path, PATHINFO_EXTENSION);
				
			  // echo $uploads_dir."/".$filename;exit;
				 move_uploaded_file($tmp_name, $uploads_dir."/".$filename);
				
				$data = array('deathuser_id'=>$obituary_id,'image_url'=>$name,'main_image'=>0,'name'=>$path,'created'=>date('Y-m-d H:i:s'));
				
				$re = $this->getImageUploadTable()->savefile($data); 
				$rrr=$this->getImageUploadTable()->getById($obituary_id); 
						$credate=0;	
$did=0;	
				$rows = array();
        foreach ($rrr as $row) {
        	if($credate<$row['created']) {
        		$credate=$row['created'];
        		$did=$row['id'];
        	}
       		
        }
        //echo $did;
       // exit;
       $this->getImageUploadTable()->updatedeathuser($did);
				$this->redirect()->toRoute('uploadd');
				return array('ext'=>$ext);
			}
		}
		else{
			echo 'You are Already Uploaded 8 files';
			return $this->redirect()->toUrl('uploadd');
		}
		$this->layout('layout/custom');
	}
	
	public function showAction()
	{
		$results = $this->getUploadTable()->fetchAll();		
		$this->layout('layout/custom');
		return array('results'=>$results);
	}
	public function playAction()
	{
		$id = (int) $this->params()->fromQuery('id',0);
	
		if(!$id){return $this->redirect()->toUrl('home');	 }
        $result = $this->getUploadTable()->getName($id);
        $doc_name = $result['file_name'];
        $ext = $result['extension'];
		
        $path = '/addthis/upload/'.$doc_name;
		$this->layout('layout/custom');
		return array('path'=>$path,'ext'=>$ext);
	}
	
	public function downloadAction()
	{
        $id = (int) $this->params()->fromQuery('id',0);
	   if(!$id){return $this->redirect()->toUrl('home');	 }
        $result = $this->getUploadTable()->getName($id);
        $doc_name = $result['file_name'];
        
        //$path = '/addthis/upload/'.$doc_name;
       $path =  $this->request->getBasePath().'/addthis/upload/'.$doc_name;
	  // print $path;
        $abc = $this->output_file($path,basename($path));
        die;
	}
	protected function output_file($file, $name, $mime_type='')
	{
		if(!is_readable($file)) die('File not found or inaccessible!');

		$size = filesize($file);
		$name = rawurldecode($name);
		$known_mime_types=array(
				"pdf" => "application/pdf",
				"txt" => "text/plain",
				"html" => "text/html",
				"htm" => "text/html",
				"exe" => "application/octet-stream",
				"zip" => "application/zip",
				"doc" => "application/msword",
				"xls" => "application/vnd.ms-excel",
				"ppt" => "application/vnd.ms-powerpoint",
				"gif" => "image/gif",
				"png" => "image/png",
				"jpeg"=> "image/jpg",
				"jpg" => "image/jpg",
				"php" => "text/plain"
			);
		if($mime_type==''){
			$file_extension = strtolower(substr(strrchr($file,"."),1));
			if(array_key_exists($file_extension, $known_mime_types)){
				$mime_type=$known_mime_types[$file_extension];
			} else {
				$mime_type="application/force-download";
			}
		}

		//@ob_end_clean();

		if(ini_get('zlib.output_compression'))
		ini_set('zlib.output_compression', 'Off');
		header('Content-Type: ' . $mime_type);
		header('Content-Disposition: attachment; filename="'.$name.'"');
		header("Content-Transfer-Encoding: binary");
		header('Accept-Ranges: bytes');
		header("Cache-control: private");
		header('Pragma: private');
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		if(isset($_SERVER['HTTP_RANGE']))
		{
			list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
			list($range) = explode(",",$range,2);
			list($range, $range_end) = explode("-", $range);
				$range=intval($range);
			if(!$range_end) {
				$range_end=$size-1;
			} else {
			$range_end=intval($range_end);
			}
			$new_length = $range_end-$range+1;
			header("HTTP/1.1 206 Partial Content");
			header("Content-Length: $new_length");
			header("Content-Range: bytes $range-$range_end/$size");
		} else {
				$new_length=$size;
				header("Content-Length: ".$size);
			}
		$chunksize = 1*(1024*1024);
		$bytes_send = 0;
		if ($file = fopen($file, 'r'))
		{
			if(isset($_SERVER['HTTP_RANGE']))
			fseek($file, $range);

			while(!feof($file) &&
			(!connection_aborted()) &&
			($bytes_send<$new_length)
			)
			{
				$buffer = fread($file, $chunksize);
				print($buffer);
				flush();
				$bytes_send += strlen($buffer);
			}
			fclose($file);
		} 
		else

			die('Error - can not open file.');
			die();
	}
	
	public function achievementAction()
	{
		$results_thmb = $this->getUploadTable()->fetchFromAdmin();
		// print "<pre>";
		// print_r($results_thmb);die;
		
		$this->layout('layout/custom');
		return array('results_thmb'=>$results_thmb);
	}
	public function personalAction()
	{
			$results_thmb 	= $this->getUploadTable()->fetchFromAdmin();
		
		$this->layout('layout/custom');
		return array('results_thmb'=>$results_thmb);
	}
	public function footprintAction()
	{
	
		$results_thmb 	= $this->getUploadTable()->fetchFromAdmin();
		
		$this->layout('layout/custom');
		return array('results_thmb'=>$results_thmb);
	
	}
	
	
	

	
    /**
     * Get Death user Table Object by a service manager to perform CURD operations
     * 
     * @author developed by Trs Software Solutions
     * @return entity object
     **/
     public function getUserTributesTable() {
        if (!$this->usertributesTable) {
            $sm = $this->getServiceLocator();
            $this->usertributesTable = $sm->get('Application\Model\UserTributesTable');
        }

        return $this->usertributesTable;

    }
public function getUserPrivacyTable() {
        if (!$this->userPrivacyTable) {
            $sm = $this->getServiceLocator();
            $this->userPrivacyTable = $sm->get('Application\Model\UserPrivacyTable');
        }

        return $this->userPrivacyTable;

    }
	public function getSchoolTable() {
        if (!$this->schoolTable) {
            $sm = $this->getServiceLocator();
            $this->schoolTable = $sm->get('Application\Model\SchoolTable');
        }

        return $this->schoolTable;

    }
    public function getSearchObituaryTable() {
        if (!$this->searchObituaryTable) {
            $sm = $this->getServiceLocator();
         // print_r("sri ".$sm->get('Application\Model\SearchObituaryTable'));exit;  
            $this->searchObituaryTable = $sm->get('Application\Model\SearchObituaryTable');
     
        }
        
        return $this->searchObituaryTable;

    }
	public function getCollegeTable() {
        if (!$this->collegeTable) {
            $sm = $this->getServiceLocator();
            $this->collegeTable = $sm->get('Application\Model\CollegeTable');
        }
      return $this->collegeTable;
    }
	
	public function getUniversityTable(){
	 // echo "hello";
	 if(!$this->universityTable){
	  $sm = $this->getServiceLocator();
	  $this->universityTable = $sm->get('Application\Model\UniversityTable');
	  }
	  return $this->universityTable;
	}
	public function getOtherTable(){
	
	  if(!$this->otherTable){
	  $sm = $this->getServiceLocator();
	  $this->otherTable = $sm->get('Application\Model\OtherTable');
	  }
	return $this->otherTable;
	
	 }
	public function getCauseofDeathTable() {
        if (!$this->causeofDeathTable) {
            $sm = $this->getServiceLocator();
            $this->causeofDeathTable = $sm->get('Application\Model\CauseofDeathTable');
        }

        return $this->causeofDeathTable;

    }
	
	public function getCountryofDeathTable() {
        if (!$this->countryofDeathTable) {
            $sm = $this->getServiceLocator();
            $this->countryofDeathTable = $sm->get('Application\Model\CountryofDeathTable');
        }

        return $this->countryofDeathTable;

    }
	public function getUserFamilyDetailsTable() {
        if (!$this->userfamilydetailsTable) {
            $sm = $this->getServiceLocator();
            $this->userfamilydetailsTable = $sm->get('Application\Model\UserFamilyDetailsTable');
        }
        return $this->userfamilydetailsTable;

    }
	public function getDeathUserTable() {
        if (!$this->deathUserTable) {
            $sm = $this->getServiceLocator();
            $this->deathUserTable = $sm->get('Application\Model\DeathUserTable');
        }

        return $this->deathUserTable;

    }
public function getDeathUserGuestBookTable() {
        if (!$this->deathUserGuestBookTable) {
            $sm = $this->getServiceLocator();
            $this->deathUserGuestBookTable = $sm->get('Application\Model\DeathUserGuestBookTable');
        }

        return $this->deathUserGuestBookTable;

    }
public function getDeathUserTributeTable() {
        if (!$this->deathUserTributeTable) {
            $sm = $this->getServiceLocator();
            $this->deathUserTributeTable = $sm->get('Application\Model\DeathUserTributeTable');
        }

        return $this->deathUserTributeTable;

    }
	public function getUserTable() {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Application\Model\UserTable');
        }

        return $this->userTable;

    }
	public function getUploadTable() {
        if (!$this->uploadTable) {
            $sm = $this->getServiceLocator();
            $this->uploadTable = $sm->get('Application\Model\UploadTable');
        }

        return $this->uploadTable;

    }
  public function getImageUploadTable() {
        if (!$this->imageUploadTable) {
            $sm = $this->getServiceLocator();
            $this->imageUploadTable = $sm->get('Application\Model\ImageUploadTable');
        }

        return $this->imageUploadTable;

    }
    /**
     * Get Death user Meta Table Object by a service manager to perform CURD operations
     * 
     * @author developed by Trs Software Solutions
     * @return entity object
     **/
    private function getDeathUserMetaTable()
    {
        if (!$this->deathUserMetaTable) {
            $sm = $this->getServiceLocator();
            $this->deathUserMetaTable = $sm->get('Application\Model\DeathUserMetaTable');
        }

        return $this->deathUserMetaTable;
    }
    private function getDeathUserDonateTable()
    {
        if (!$this->deathUserDonateTable) {
            $sm = $this->getServiceLocator();
            $this->deathUserDonateTable = $sm->get('Application\Model\DeathUserDonateTable');
        }

        return $this->deathUserDonateTable;
    }
	
	// End here //
	

    public function getCmsTable() {
        if (!$this->cmsTable) {
            $sm = $this->getServiceLocator();
            $this->cmsTable = $sm->get('Admin\Model\CmsTable');
        }
        return $this->cmsTable;
    }
	
	public function getlanguageRecords() {
        
		return array("xyz"=>$usstates);
    }

}
