float intensity = 1.0;
float radius = 10.0;
float sunchecktimer = 30.0;

key owner;
key primkey;

integer autoon = TRUE;
integer apichan = -9548;
integer localchan;
integer gListener;
integer face = ALL_SIDES;

string lightingstate = "off";
string apiname = "ZLAPI";

lightchanger(string ls)
{
    if (ls == "on")
    {
        lightingstate = "on";
        llSetPrimitiveParams([PRIM_POINT_LIGHT, TRUE, <1.0,1.0,1.0>, intensity, radius, 0.75, PRIM_FULLBRIGHT, face, TRUE]);
    }
    else if (ls == "off")
    {
        lightingstate = "off";
        llSetPrimitiveParams([PRIM_POINT_LIGHT, FALSE, <0.0,0.0,0.0>, 0.0, 0.0, 0.0, PRIM_FULLBRIGHT, face, FALSE]);
    }
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
    
    touch_end(integer n)
    {
        key toucher = llDetectedKey(0);
        if (toucher == owner)
        {
            gListener = llListen(localchan, "", owner, "");
            string autostate;
            string intensitystate;
            if (autoon) {
                autostate = "On";
            }else{
                autostate = "Off";
            }
            
            if (intensity == 1.0) {
                intensitystate = "Bright";
            }else if (intensity == 0.5) {
                intensitystate = "Med";
            }else if (intensity == 0.1) {
                intensitystate = "Low";
            }else if (intensity == 0.0) {
                intensitystate = "Off";
            }
            llDialog(owner, "Please choose a option\nAuto on at dust is set to " + autostate + "\n Intensity set to " + intensitystate, ["Bright", "Med", "Low", "On", "Off", "Auto", "RESET", "EXIT"], localchan);
        }
    }

    link_message(integer s, integer num, string str, key id)
    {
        if (str == "Bright") {
            intensity = 1.0;
            lightchanger(lightingstate);
        }
        if (str == "Med") {
            intensity = 0.5;
            lightchanger(lightingstate);
        }
        if (str == "Low") {
            intensity = 0.1;
            lightchanger(lightingstate);
        }
        if (str == "On" || str == "on") {
            lightchanger("on");
            autoon = FALSE;
            llSetTimerEvent(0.0);
        }
        if (str == "Off" || str == "off") {
            lightchanger("off");
            autoon = FALSE;
            llSetTimerEvent(0.0);
        }
        if (str == "Auto") {
            if (autoon) {
                autoon = FALSE;
                llSetTimerEvent(0.0);
            }else{
                autoon = TRUE;
                llSetTimerEvent(sunchecktimer);
            }
        }
        if (str == "RESET") {
            llResetScript();
        }
    }
    
    listen(integer c, string n, key id, string msg)
    {
        if (c == apichan) {
            if (llGetOwnerKey(id) == owner) {
                list apilist = llParseString2List(llUnescapeURL(msg), ["="], []);
                string apimsg0 = llList2String(apilist, 0);
                string apimsg1 = llList2String(apilist, 1);
                if (apimsg0 == apiname && apimsg1 == "LIGHTS") {
                    string apimsg2 = llList2String(apilist, 2);
                    if (apimsg2 == "bright") {
                        intensity = 1.0;
                        lightchanger(lightingstate);
                    }
                    if (apimsg2 == "med") {
                        intensity = 0.5;
                        lightchanger(lightingstate);
                    }
                    if (apimsg2 == "low") {
                        intensity = 0.1;
                        lightchanger(lightingstate);
                    }
                    if (apimsg2 == "on") {
                        lightchanger("on");
                        autoon = FALSE;
                        llSetTimerEvent(0.0);
                    }
                    if (apimsg2 == "off") {
                        lightchanger("off");
                        autoon = FALSE;
                        llSetTimerEvent(0.0);
                    }
                    if (apimsg2 == "auto") {
                        if (autoon) {
                            autoon = FALSE;
                            llSetTimerEvent(0.0);
                        }else{
                            autoon = TRUE;
                            llSetTimerEvent(sunchecktimer);
                        }
                    }
                    if (apimsg2 == "RESET") {
                        llResetScript();
                    }
                }
            }
        }else if (c == localchan && id == owner) {
            if (msg == "Bright") {
                intensity = 1.0;
                lightchanger(lightingstate);
            }
            if (msg == "Med") {
                intensity = 0.5;
                lightchanger(lightingstate);
            }
            if (msg == "Low") {
                intensity = 0.1;
                lightchanger(lightingstate);
            }
            if (msg == "On") {
                lightchanger("on");
                autoon = FALSE;
                llSetTimerEvent(0.0);
            }
            if (msg == "Off") {
                lightchanger("off");
                autoon = FALSE;
                llSetTimerEvent(0.0);
            }
            if (msg == "Auto") {
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
                llResetScript();
            }
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