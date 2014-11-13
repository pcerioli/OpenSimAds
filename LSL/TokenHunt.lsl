//string grid = "InWorldz";
string grid = "GCG";
integer price = 1000;
string tokenworthstring = "0.01";
float tokenworth = 0.01; // same as tokenworthstring but without the quotes
string currency = "I'z";
string url = "http://www.opensimads.com/ossl/tokenhunt.php?method=post";
string buildtype = "dev"; // rb = recommanded, dev = development

string dbid;
string sim;
string parcel;

key owner;
key primkey;
key toucher = NULL_KEY;

integer face = ALL_SIDES;
integer localchan;
integer gListener;
integer touchedtime;
integer holdtimercountdown = 30;
integer randnumber;

float resettimer;
float moneyin;

key http_onrez;
key http_deleterez;
key http_get_toucher;
key http_claim_token;
key http_update_money;

vector pos;

float volume = 1.0;
string touchedsound = "67cc2844-00f3-2b3c-b991-6418d01e1bb7";
string claimsound = "77a018af-098e-c037-51a6-178f05877c6f";
list particlelist = [PSYS_PART_FLAGS, PSYS_PART_WIND_MASK | PSYS_PART_EMISSIVE_MASK, PSYS_SRC_PATTERN, PSYS_SRC_PATTERN_EXPLODE, PSYS_SRC_TEXTURE, "dollarsign",PSYS_PART_START_GLOW, 0.5];
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
	string body = "type=rez&grid="+llEscapeURL(grid)+"&dbid="+llEscapeURL(dbid)+"&owner="+llEscapeURL((string)owner)+"&ownername="+llEscapeURL(llKey2Name(owner))+"&sim="+llEscapeURL(sim)+"&parcel="+llEscapeURL(parcel)+"&primkey="+llEscapeURL((string)primkey)+"&pos="+llEscapeURL((string)pos)+"&tokenworth="+(string)tokenworth;
    http_onrez = llHTTPRequest(url, httplist, body);
}
list make_ordered_buttons(integer input)
{
    string output = "7, 8, 9, 4, 5, 6, 1, 2, 3, 0, Exit, Unknown";
    return llCSV2List(output);
}
default
{
	on_rez(integer start_param)
    {
        primreset();
    }
    state_entry()
    {
        llParticleSystem([]);
        llSetAlpha(1.0, face);
        llTargetOmega(<0.0,0.0,0.0>,0.0,0.0);
        if (llGetOwner() == llGetCreator() && buildtype == "rb" || buildtype == "dev" && llGetOwner() != llGetCreator()) {
            // do nothing if the person that owns this script is the creator to avoid spam in the database
            // ONLY should be triggered when creating the prims for shipment.
        }else if (llGetOwner() != llGetCreator() && buildtype == "rb" || buildtype == "dev" && llGetOwner() == llGetCreator()) {
            llSetPayPrice(PAY_HIDE, [PAY_HIDE, PAY_HIDE, PAY_HIDE, PAY_HIDE]);
            llSetText("Starting up...", <1.0,1.0,1.0>, 1.0);
            resettimer = holdtimercountdown * 2;
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
            string msg1 = llList2String(bodylist, 1); // dbid
            float msg2 = llList2Float(bodylist, 2); // cash on hand
            if (msg0 == "rezzed" || msg0 == "alreadyrezzed") {
                dbid = msg1;
                moneyin = msg2;
                llSetObjectDesc(dbid);
                llSetText("Requesting money permission...", <1.0,1.0,1.0>, 1.0);
                llOwnerSay("Requesting money permission so you can add money to this token. That money will then need to be sent to "+llKey2Name(llGetCreator())+" to be able to pay your visitors.");
                llRequestPermissions(owner, PERMISSION_DEBIT);
                //state ready;
            }else if (msg0 == "failed2rez") {
                llSetText("Unable to rez this token in our system.", <1.0,0.0,0.0>, 1.0);
            }else{
                llOwnerSay(body);
                llSetText("Unable to contact server!", <1.0,0.0,0.0>, 1.0);
            }
        }
    }
    run_time_permissions(integer perm)
    {
        if(perm & PERMISSION_DEBIT) {
            llOwnerSay("Permission accepted. Switching to a ready state.");
            state ready;
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
        toucher = NULL_KEY;
        llSetText("", <0.0,0.0,0.0>, 0.0);
        localchan = (integer)("0x80000000"+llGetSubString((string)primkey,-8,-1));
        dbid = llGetObjectDesc();
        llSetAlpha(1.0, face);
        llSetPrimitiveParams([PRIM_PHANTOM, TRUE]);
        llTargetOmega(<0.0,0.0,0.1>,TWO_PI,0.2);
        llSetPayPrice(price, [25, 50, 100, 1000]);
        if (moneyin == 0.00) {
            state outofcash;
        }
    }
    touch_end(integer n)
    {
        toucher = llDetectedKey(0);
        if (toucher != owner) {
            gListener = llListen(localchan, "", owner, "");
            llDialog(owner, "How may i help you?", ["EXIT", "RESET", "DELETE"], localchan);
        }else if (toucher == owner) {
            llSensor("", toucher, AGENT, 10.0, PI);
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
            if (msg == "DELETE") {
                string body = "type=deleteprim&grid="+llEscapeURL(grid)+"&dbid="+llEscapeURL(dbid)+"&owner="+llEscapeURL((string)owner);
                http_deleterez = llHTTPRequest(url, httplist, body);
            }
        }
    }
    sensor(integer n)
    {
        string body = "type=gettoucher&grid="+llEscapeURL(grid)+"&dbid="+llEscapeURL(dbid)+"&toucher="+llEscapeURL((string)toucher)+"&touchername="+llEscapeURL(llKey2Name(toucher));
        http_get_toucher = llHTTPRequest(url, httplist, body);
    }
    no_sensor()
    {
        llInstantMessage(toucher, "Sorry but you need to be within 10 meters of this token to claim it.");
        toucher = NULL_KEY;
    }
    http_response(key request_id, integer s, list metadata, string body)
    {
        if (request_id == http_get_toucher)
        {
            if (body == "nottouched") {
                state touched;
            }else if (body == "touched") {
                llInstantMessage(toucher, "You have already claimed this token. Please come back in 24 hours since you last claimed this token.");
                toucher = NULL_KEY;
            }else if (body == "outofcash") {
                toucher = NULL_KEY;
                state outofcash;
            }
        }
        if (request_id == http_update_money) {
            list bodylist = llParseString2List(llUnescapeURL(body), ["="], []);
            string msg0 = llList2String(bodylist, 0); // command
            integer msg1 = llList2Integer(bodylist, 1); // amount
            float msg2 = llList2Float(bodylist, 2); // total cash on hand
            if (msg0 == "success") {
                llGiveMoney(llGetCreator(), msg1);
                llInstantMessage(owner, "Sending money to "+llKey2Name(llGetCreator())+" so they can pay your visitors.");
                moneyin = msg2;
                llInstantMessage(owner, "Money accepted. Token now has "+currency+" "+(string)moneyin+" and is ready to be claimed.");
            }
        }
        if (request_id == http_deleterez) {
            list bodylist = llParseString2List(llUnescapeURL(body), ["="], []);
            string msg0 = llList2String(bodylist, 0); // command
            string msg1 = llList2String(bodylist, 1); // amount
            if (msg0 == "success") {
                llInstantMessage(owner, "Token derezzing.\n
All left over money in this token have been moved to your OpensimAds avatar.\n
Your balance is "+currency+" "+msg1+"\n
You may withdrawal from any of our ATM's.");
                llSleep(2.5);
                llDie();
            }else{
                llInstantMessage(owner, "**ERROR!**\nContact support ASAP!\nERROR MESSAGE:\n"+body);
            }
        }
    }
    money(key id, integer amount)
    {
        if (id == owner) {
            string body = "type=updatemoney&grid=" + llEscapeURL(grid) + "&dbid=" + llEscapeURL(dbid) + "&amount=" + llEscapeURL((string)moneyin);
            http_update_money = llHTTPRequest(url, httplist, body);
        }else{
            llInstantMessage(owner, llKey2Name(id)+" tried to put money on this token but the script is writen to prevent anyone but the prim owner to add money.");
            llInstantMessage(id, "You are not the owner of this token. If you like to donate please find a donate box for "+llKey2Name(owner));
            llGiveMoney(id, amount);
        }
    }
}
state outofcash
{
    state_entry()
    {
        llSetText("Out of funds.\nPlease add more money.", <1.0,0.0,0.0>, 1.0);
        llSetPayPrice(price, [25, 50, 100, 1000]);
    }
    money(key id, integer amount)
    {
        if (id == owner) {
            moneyin = moneyin + (float)amount;
            string body = "type=updatemoney&grid=" + llEscapeURL(grid) + "&dbid=" + llEscapeURL(dbid) + "&amount=" + llEscapeURL((string)moneyin);
            http_update_money = llHTTPRequest(url, httplist, body);
        }else{
            llInstantMessage(owner, llKey2Name(id)+" tried to put money on this token but the script is writen to prevent anyone but the prim owner to add money.");
            llInstantMessage(id, "You are not the owner of this token. If you like to donate please find a donate box for "+llKey2Name(owner));
            llGiveMoney(id, amount);
        }
    }
    http_response(key request_id, integer s, list metadata, string body)
    {
        if (request_id == http_update_money) {
            list bodylist = llParseString2List(llUnescapeURL(body), ["="], []);
            string msg0 = llList2String(bodylist, 0); // command
            integer msg1 = llList2Integer(bodylist, 1); // dbid
            float msg2 = llList2Float(bodylist, 2); // cash on hand
            if (msg0 == "success") {
                llGiveMoney(llGetCreator(), msg1);
                llInstantMessage(owner, "Sending money to "+llKey2Name(llGetCreator())+" so they can pay your visitors.");
                moneyin = msg2;
                llInstantMessage(owner, "Money accepted. Token now has "+currency+" "+(string)moneyin+" and is ready to be claimed.");
                state ready;
            }
        }
    }
}
state touched
{
    state_entry()
    {
        llSetTimerEvent(1.0);
        touchedtime = llGetUnixTime() + holdtimercountdown;
        llTargetOmega(<0.0,0.0,0.1>,TWO_PI,0.2);
        llSetPrimitiveParams([PRIM_PHANTOM, TRUE]);
        llSetAlpha(0.5, face);
        llSetPayPrice(PAY_HIDE, [PAY_HIDE, PAY_HIDE, PAY_HIDE, PAY_HIDE]);
        llPlaySound(touchedsound, volume);
        llInstantMessage(toucher, "I will pay you "+currency+" "+tokenworthstring+" but first you must wait "+(string)llRound(holdtimercountdown)+" seconds.\nMeanwhile you may find more tokens in this area.");
    }
    timer()
    {
        integer remainingtime = touchedtime - llGetUnixTime();
        llSetText("Token is being claimed by "+llKey2Name(toucher)+"\nRemaining Time: "+(string)remainingtime+" seconds.", <1.0,1.0,1.0>, 1.0);
        if (remainingtime <= 00) {
            state claiming;
        }
    }
}
state claiming
{
    state_entry()
    {
        llSetTimerEvent(0.0);
        llSensor("", toucher, AGENT, 10.0, PI);
        llSetPrimitiveParams([PRIM_PHANTOM, TRUE]);
        llSetText(llKey2Name(toucher)+" is claiming this token", <0.0,1.0,0.0>, 1.0);
        llSetPayPrice(PAY_HIDE, [PAY_HIDE, PAY_HIDE, PAY_HIDE, PAY_HIDE]);
        
    }
    sensor (integer n) {
        gListener = llListen(localchan, "", toucher, "");
        randnumber = (integer)llFrand(9.0);
        if (randnumber == 0) {
            randnumber = 1;
        }else
        if (randnumber >= 9) {
            randnumber = 9;
        }
        resettimer = resettimer * (float)randnumber;
        string diamsg = "\nYour random number is "+(string)randnumber+"\n
        You have 30 seconds to pick the correct number that is shown above.";
        llDialog(toucher, diamsg, make_ordered_buttons(9), localchan);
        llSetTimerEvent(30.0);
    }
    no_sensor() {
        llSay(0, llKey2Name(toucher)+" was not within range to claim this token. Resetting for another avatar.");
        state resttoken;
    }
    listen(integer chan, string name, key id, string msg)
    {
        llListenRemove(gListener);
        if (id == toucher && chan == localchan) {
            if (msg == (string)randnumber) {
                string body = "type=claimtoken&grid=" + llEscapeURL(grid) + "&dbid=" + llEscapeURL(dbid) + "&toucher=" + llEscapeURL((string)toucher)+"&touchername="+llEscapeURL(llKey2Name(toucher));
                http_claim_token = llHTTPRequest(url, httplist, body);
            }else if (msg != (string)randnumber) {
                toucher = NULL_KEY;
                llSay(0, "Incorrect number. Please try again in "+(string)llRound(resettimer)+" seconds.");
                state resttoken;
            }
        }
    }
    http_response(key request_id, integer s, list metadata, string body)
    {
        if (request_id == http_claim_token)
        {
            llSetText(llKey2Name(toucher)+" has claimed this token", <0.0,1.0,0.0>, 1.0);
            list bodylist = llParseString2List(llUnescapeURL(body), ["="], []);
            key msg0 = llList2Key(bodylist, 0);
            string msg1 = llList2String(bodylist, 1);
            moneyin = llList2Float(bodylist, 2); // cash on hand
            //llParticleSystem(particlelist); // particles seem to not work correctly in InWorldz
            string sendmsg = "Congratulations. You have earned "+tokenworthstring+"\n";
            sendmsg += "Total Earnings: "+currency+" "+msg1+"\n";
            sendmsg += "You can cash out at any OpensimAds ATM once you have earned at least 1 "+currency;
            llInstantMessage(msg0, sendmsg);
            llSleep(5.0);
            state resttoken;
        }
    }
    timer()
    {
        llSay(0, "You did not answer in a timely fashion.");
        state resttoken;
    }
}
state resttoken
{
    state_entry()
    {
        llParticleSystem([]);
        llSetTimerEvent(0.0);
        llSetText("", <0.0,0.0,0.0>, 0.0);
        toucher = NULL_KEY;
        llSetAlpha(0.0, face);
        llSetTimerEvent(resettimer);
        llTargetOmega(<0.0,0.0,0.0>,0.0,0.0);
        llSetPrimitiveParams([PRIM_PHANTOM, TRUE]);
        llSetPayPrice(PAY_HIDE, [PAY_HIDE, PAY_HIDE, PAY_HIDE, PAY_HIDE]);
    }
    timer()
    {
        state ready;
    }
}