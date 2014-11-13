string grid = "InWorldz";
string url = "http://www.opensimads.com/ossl/votebox.php?method=post";
string buildtype = "dev"; // rb = recommanded, dev = development
string dbid;
string sim;
string parcel;
string rezday;

key owner;
key primkey;
key toucher = NULL_KEY;

key http_onrez;
key http_deleterez;
key http_voted;
key http_get_votes;
key http_del;

integer face = ALL_SIDES;
integer localchan;
integer gListener;
integer votes;
integer votestoday;

float resettimer = 86400.0;

float volume = 1.0;
string touchedsound = "67cc2844-00f3-2b3c-b991-6418d01e1bb7";
list httplist = [HTTP_METHOD, "POST", HTTP_VERIFY_CERT, FALSE, HTTP_MIMETYPE,"application/x-www-form-urlencoded"];
primreset()
{
    llResetScript();
}
ping()
{
	if (llGetObjectDesc() != "") {
        dbid = llGetObjectDesc();
    }else{
        dbid = "";
    }
    owner = llGetOwner();
    primkey = llGetKey();
    pos = llGetPos();
    sim = llGetRegionName();
    list parceldetails = llGetParcelDetails(pos, [PARCEL_DETAILS_NAME]);
    parcel = llList2String(parceldetails, 0);
	string body = "type=rez&grid="+llEscapeURL(grid)+"&dbid="+llEscapeURL(dbid)+"&owner="+llEscapeURL((string)owner)+"&ownername="+llEscapeURL(llKey2Name(owner))+"&sim="+llEscapeURL(sim)+"&parcel="+llEscapeURL(parcel)+"&primkey="+llEscapeURL((string)primkey)+"&pos="+llEscapeURL((string)pos);
    http_onrez = llHTTPRequest(url, httplist, body);
}
setprimtext(string msg)
{
    string setmsg;
    if (msg == "Def") {
        setmsg = "Votes today: "+(string)votestoday+"\nTotal votes: "+(string)votes+" since "+rezday;
    }else{
        setmsg = msg;
    }
    llSetText(setmsg, <1.0,1.0,1.0>, 1.0);
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
            setprimtext("Starting up...");
            if (llGetObjectDesc() != "") {
            	dbid = llGetObjectDesc();
            }
            ping();
        }
    }
    http_response(key request_id, integer s, list metadata, string body)
    {
        if (request_id == http_onrez)
        {
            list bodylist = llParseString2List(llUnescapeURL(body), ["="], []);
            string msg0 = llList2String(bodylist, 0); // command
            if (msg0 == "rezzed" || msg0 == "alreadyrezzed") {
            	dbid = llList2String(bodylist, 1); // dbid
                votestoday = llList2Integer(bodylist, 2);
            	votes = llList2Integer(bodylist, 3); // vote count
            	rezday = llList2String(bodylist, 4); // rez day in readable format
                llSetObjectDesc(dbid);
                state ready;
            }else if (msg0 == "failed2rez") {
                llSetText("Unable to rez this vote box in our system.", <1.0,0.0,0.0>, 1.0);
            }else{
                llOwnerSay(body);
                llSetText("Unable to contact server!", <1.0,0.0,0.0>, 1.0);
            }
        }
    }
}
state ready
{
	on_rez(integer start_param)
    {
        primreset();
    }
    state_entry()
    {
    	setprimtext("Def");
        localchan = (integer)("0x80000000"+llGetSubString((string)primkey,-8,-1));
        dbid = llGetObjectDesc();
        llSetTimerEvent(resettimer);
    }
    touch_end(integer n)
    {
    	toucher = llDetectedKey(0);
    	if (toucher == owner) {
    		gListener = llListen(localchan, "", owner, "");
            llDialog(owner, "How may i help you?", ["EXIT", "RESET", "COUNT", "DELETE"], localchan);
    	}else if (toucher != owner) {
    		string body = "type=voted&grid="+llEscapeURL(grid)+"&dbid="+llEscapeURL(dbid)+"&toucher="+llEscapeURL((string)toucher);
    		http_voted = llHTTPRequest(url, httplist, body);
    	}
    }
    listen(integer chan, string name, key id, string msg)
    {
    	if (chan == localchan && id == owner) {
            llListenRemove(gListener);
            if (msg == "EXIT") {
                // do nothing and close out the listen
            }
            if (msg == "RESET") {
                primreset(); // reset the script
            }
            if (msg == "COUNT") {
                string body = "type=getvotes&grid="+llEscapeURL(grid)+"&dbid="+llEscapeURL(dbid);
                http_get_votes = llHTTPRequest(url, httplist, body);
            }
            if (msg == "DELETE") {
                string body = "type=deleteprim&grid="+llEscapeURL(grid)+"&dbid="+llEscapeURL(dbid);
                http_del = llHTTPRequest(url, httplist, body);
            }
    	}
    }
    http_response(key request_id, integer s, list metadata, string body)
    {
        if (request_id == http_voted) {
            list bodylist = llParseString2List(llUnescapeURL(body), ["="], []);
            string msg0 = llList2String(bodylist, 0); // command
            if (msg0 == "success") {
            	votes = llList2Integer(bodylist, 1); // vote count
            	rezday = llList2String(bodylist, 2); // rez day in readable format
            	llInstantMessage(toucher, "Thank you! Your vote has been recorded. Please come back in 24 hours to vote again.");
                setprimtext("Total votes: "+(string)votes);
            }else if (msg0 == "alreadyvoted") {
            	llInstantMessage(toucher, "You have already voted. Please come back in 24 hours since you last touched this vote box.");
            }else if (msg0 == "failed2rez") {
                llInstantMessage(toucher, "Unable to save your vote for this place in our system.");
            }
            toucher = NULL_KEY;
        }
        if (request_id == http_get_votes) {
            list bodylist = llParseString2List(llUnescapeURL(body), ["="], []);
            votestoday = llList2Integer(bodylist, 0);
            votes = llList2Integer(bodylist, 1);
            llInstantMessage(owner, "Votes today: "+(string)votestoday+"\nTotal votes: "+(string)votes);
            setprimtext("Def");
        }
        if (request_id == http_del) {
            if (body == "deleteprim") {
                llDie();
            }else{
                llInstantMessage(owner, body);
            }
        }
    }
    timer()
    {
        primreset();
    }
}