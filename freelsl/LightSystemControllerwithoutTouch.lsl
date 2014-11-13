float intensity = 1.0;
float radius = 10.0;
float sunchecktimer = 30.0;

key owner;
key primkey;

integer autoon = TRUE;
integer apichan = -9548;
integer localchan;
integer gListener;
integer face = 1;

string lightingstate = "off";
string apiname = "ZLAPI";

lightchanger(string ls)
{
    if (ls == "on")
    {
        lightingstate = "on";
        //llRegionSay(apichan, apiname+"=LIGHTS=on");
    }
    else if (ls == "off")
    {
        lightingstate = "off";
        //llRegionSay(apichan, apiname+"=LIGHTS=off");
    }
    llMessageLinked(LINK_SET, 0, ls, "");
}

default
{
    on_rez(integer start_param)
    {
        llResetScript();
    }
    
    state_entry()
    {
        owner = llGetOwner();
        primkey = llGetKey();
        llListen(apichan, "", "", "");
        localchan = (integer)("0x80000000"+llGetSubString((string)primkey,-8,-1));
        if (autoon) {
            llSetTimerEvent(sunchecktimer);
        }else{
            llSetTimerEvent(0.0);
        }
    }
    
    link_message(integer sender_num, integer num, string str, key id)
    {
        list bodylist = llParseString2List(llUnescapeURL(str), ["="], []);
        string msg0 = llList2String(bodylist, 0);
        string msg1 = llList2String(bodylist, 1);
        string msg2 = llList2String(bodylist, 2);
        if (msg0 == apiname && msg1 == "LIGHTS") {
            if (msg2 == "Bright") {
                intensity = 1.0;
                lightchanger(lightingstate);
                //llRegionSay(apichan, apiname+"=LIGHTS=bright");
            }
            if (msg2 == "Med") {
                intensity = 0.5;
                lightchanger(lightingstate);
                //llRegionSay(apichan, apiname+"=LIGHTS=med");
            }
            if (msg2 == "Low") {
                intensity = 0.1;
                lightchanger(lightingstate);
                //llRegionSay(apichan, apiname+"=LIGHTS=low");
            }
            if (msg2 == "On") {
                lightchanger("on");
                autoon = FALSE;
                llSetTimerEvent(0.0);
                //llRegionSay(apichan, apiname+"=LIGHTS=on");
            }
            if (msg2 == "Off") {
                lightchanger("off");
                autoon = FALSE;
                llSetTimerEvent(0.0);
                //llRegionSay(apichan, apiname+"=LIGHTS=off");
            }
            if (msg2 == "Auto") {
                //llRegionSay(apichan, apiname+"=LIGHTS=auto");
                if (autoon) {
                    autoon = FALSE;
                    llSetTimerEvent(0.0);
                }else{
                    autoon = TRUE;
                    llSetTimerEvent(sunchecktimer);
                }
            }
            if (msg2 == "RESET") {
                //llRegionSay(apichan, apiname+"=LIGHTS=RESET");
                llResetScript();
            }
            llMessageLinked(LINK_SET, 0, msg2, "");
        }
    }
    
    listen(integer c, string n, key id, string msg)
    {
        if (c == localchan && id == owner) {
            if (msg == "Bright") {
                intensity = 1.0;
                lightchanger(lightingstate);
                //llRegionSay(apichan, apiname+"=LIGHTS=bright");
            }
            if (msg == "Med") {
                intensity = 0.5;
                lightchanger(lightingstate);
                //llRegionSay(apichan, apiname+"=LIGHTS=med");
            }
            if (msg == "Low") {
                intensity = 0.1;
                lightchanger(lightingstate);
                //llRegionSay(apichan, apiname+"=LIGHTS=low");
            }
            if (msg == "On") {
                lightchanger("on");
                autoon = FALSE;
                llSetTimerEvent(0.0);
                //llRegionSay(apichan, apiname+"=LIGHTS=on");
            }
            if (msg == "Off") {
                lightchanger("off");
                autoon = FALSE;
                llSetTimerEvent(0.0);
                //llRegionSay(apichan, apiname+"=LIGHTS=off");
            }
            if (msg == "Auto") {
                //llRegionSay(apichan, apiname+"=LIGHTS=auto");
                if (autoon) {
                    autoon = FALSE;
                    llSetTimerEvent(0.0);
                }else{
                    autoon = TRUE;
                    llSetTimerEvent(sunchecktimer);
                }
            }
            if (msg == "EXIT") {
                // do nothing
            }
            if (msg == "RESET") {
                //llRegionSay(apichan, apiname+"=LIGHTS=RESET");
                llResetScript();
            }
            llMessageLinked(LINK_SET, 0, msg, "");
            llListenRemove(gListener);
        }
    }
    
    timer()
    {
        vector sun = llGetSunDirection();
        if(sun.z > 0.0) {
            lightchanger("off");
        }else{
            lightchanger("on");
        }
    }
}