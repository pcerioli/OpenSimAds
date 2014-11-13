string grid = "";
integer price = 1000;
string url = "http://www.opensimads.com/ossl/buyadboard.php";
string primurl; // URL generated by llRequestURL()
string buildtype = "rb"; // rb = recommanded, dev = development
string dbid;

key owner;
key primkey;
key renter = NULL_KEY;

integer textureface = 1;
integer localchan;
integer gListener;
integer price2;
integer price3;
integer price4;

key http_onrez;
key http_deleterez;
key renter_name_query;
key http_add_renter;
key http_del_renter;

primreset()
{
    llReleaseURL(primurl);
    llResetScript();
}
ping()
{
	dbid = llGetObjectDesc();
	string body = "type=rez&grid=" + llEscapeURL(grid) + "&dbid=" + llEscapeURL(dbid) + "&primkey=" + llEscapeURL((string)primkey);
    http_onrez = llHTTPRequest(url, [HTTP_METHOD, "POST", HTTP_VERIFY_CERT, FALSE], body);
}
mainmenu(key toucher)
{
    list diaoptions = [];
    string diamsg = "Opensim Ads.\nHow may I help you today " + llKey2Name(toucher);
    gListener = llListen(localchan, "", toucher, "");
    timeoutmenu = TRUE;
    if (toucher == owner) {
        if (status == "rented") {
            diaoptions = ["RESET", "LOCK", "DELETE", "EVICT", "COUNT PRIMS", "CheckUpdate", "EXIT"];
        }else if (status == "available" || status == "") {
            diaoptions = ["RESET", "LOCK", "DELETE", "COUNT PRIMS", "CheckUpdate", "EXIT"];
        }else if (status == "locked") {
            diaoptions = ["RESET", "UNLOCK", "DELETE", "COUNT PRIMS", "CheckUpdate", "EXIT"];
        }
        llDialog(toucher, diamsg, diaoptions, localchan);
    }else if (toucher == renter) {
        if (status == "locked") {
            diaoptions = ["EXIT"];
        }else{
            diaoptions = ["LEAVE", "INFO", "TIME LEFT", "PRIM COUNT", "INVITE2GROUP", "EXIT"];
        }
        llDialog(toucher, diamsg, diaoptions, localchan);
    }else if (renter == "" || renter == NULL_KEY || renter != toucher) {
        if (status == "rented" || status == "locked") {
            diaoptions = ["INFO", "EXIT"];
        }else if (status == "available" || status == "") {
            diaoptions = ["RENT", "INFO", "EXIT"];
        }
        llDialog(toucher, diamsg, diaoptions, localchan);
    }
}
default
{
	on_rez(integer start_param)
    {
        primreset();
    }
    state_entry()
    {
        if (llGetOwner() == llGetCreator() && buildtype == "rb" || buildtype == "dev" && llGetOwner() != llGetCreator()) {
            // do nothing if the person that owns this script is the creator to avoid spam in the database
            // ONLY should be triggered when creating the prims for shipment.
        }else if (llGetOwner() != llGetCreator() && buildtype == "rb" || buildtype == "dev" && llGetOwner() == llGetCreator()) {
            owner = llGetOwner();
            llSetPayPrice(PAY_HIDE, [PAY_HIDE, PAY_HIDE, PAY_HIDE, PAY_HIDE]);
            llSetText("Starting up...", <1.0,1.0,1.0>, 1.0);
            llSetTexture(TEXTURE_BLANK, textureface);
            http_request_url = llRequestURL();
            if (llGetObjectDesc() != "") {
            	dbid = llGetObjectDesc();
            }
        }
    }
    http_request(key request_id, string method, string body)
    {
        if (method == URL_REQUEST_GRANTED) {
            primurl = body;
            if (llGetObjectDesc() != "") {
            	dbid = llGetObjectDesc();
            }else{
            	dbid = "";
            }
            string body;
            if (dbid) {
                body =  "type=rez&grid=" + llEscapeURL(grid) + "&dbid=" + llEscapeURL(dbid) + "&primkey=" + llEscapeURL((string)llGetKey()) + "&primname=" + llEscapeURL(primname) + "&primurl=" + llEscapeURL(primurl) + "&owner=" + llEscapeURL(llKey2Name(owner)) + "&ownerkey=" + llEscapeURL((string)owner);
            }else{
                body = "type=rez&grid=" + llEscapeURL(grid) + "&primkey=" + llEscapeURL((string)llGetKey()) + "&primname=" + llEscapeURL(primname) + "&primurl=" + llEscapeURL(primurl) + "&owner=" + llEscapeURL(llKey2Name(owner)) + "&ownerkey=" + llEscapeURL((string)owner);
            }
            http_onrez = llHTTPRequest(url, [HTTP_METHOD, "POST", HTTP_VERIFY_CERT, FALSE], body);
        }else if (method == URL_REQUEST_DENIED) {
            llSay(0, "Error with generating a url for this prim.");
        }else{
            list apilist = llParseString2List(llUnescapeURL(body), ["="], []);
            string msg0 = llList2String(apilist, 0);
            if (msg0 == "RESET") {
                primreset();
            }
            if (msg0 == "DEL") {
                llDie();
            }
        }
    }
    http_response(key request_id, integer s, list metadata, string body)
    {
        if (request_id == http_onrez)
        {
            list bodylist = llParseString2List(llUnescapeURL(body), ["="], []);
            string msg0 = llList2String(bodylist, 0);
            string msg1 = llList2String(bodylist, 1);
            string msg2 = llList2String(bodylist, 2);
            if (msg0 == "rezzed") {
                dbid = msg1;
                llSetObjectDesc(dbid);
                //llSetText("Requesting money permission...", <1.0,1.0,1.0>, 1.0);
                //llSay(0, "Requesting money permission. This permission is just so whenever someone rent's from here this script can accept the money and send it to you but only requested ONCE per database rez.");
                //llRequestPermissions(owner, PERMISSION_DEBIT);
                state ready;
            }else if (msg0 == "alreadyrezzed") {
                dbid = msg1;
                llSetObjectDesc(dbid);
                renter = msg2;
                llSetText("Finished setting up", <1.0,1.0,1.0>, 1.0);
                //llSay(0, "Permission for money already granted before. Finishing setup.");
                //llRequestPermissions(owner, PERMISSION_DEBIT);
                state ready;
            }else if (msg0 == "failed2rez") {
                llSetText("Failed to setup", <1.0,0.0,0.0>, 1.0);
                llOwnerSay("Unable to rez this sign in the system.");
            }
        }
    }
    //run_time_permissions(integer perm)
    //{
        //if(perm & PERMISSION_DEBIT) {
            //state ready;
        //}
    //}
}

state ready
{
    on_rez(integer start_param)
    {
        primreset();
    }
    state_entry()
    {
        llSetText("", <0.0,0.0,0.0>, 0.0);
        owner = llGetOwner();
        primkey = llGetKey();
        localchan = (integer)("0x80000000"+llGetSubString((string)primkey,-8,-1));
        dbid = llGetObjectDesc();
    }
}