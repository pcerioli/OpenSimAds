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

// for CasperLet support
integer waitingforping = FALSE;
key candidate = "";
key listeningunit = NULL_KEY;
key tenant = NULL_KEY;
list additionaltenants=[];
integer casperletchan = 77777;

lightchanger(string ls)
{
    string sendapimsg;
    if (ls == "on") {
        lightingstate = "on";
        sendapimsg = apiname+"=LIGHTS=on";
    }else if (ls == "off") {
        lightingstate = "off";
        sendapimsg = apiname+"=LIGHTS=off";
    }
    llRegionSay(apichan, sendapimsg);
    llMessageLinked(LINK_SET, 0, sendapimsg, "");
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
        llListen(apichan, "", "", ""); // GridRent
        llListen(casperletchan, "", "", ""); // CasperLet
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
        integer found = llListFindList(additionaltenants,[toucher]);
        if (toucher == owner || found != -1 || )
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

    link_message(integer sender_num, integer num, string str, key id)
    {
        list bodylist = llParseString2List(llUnescapeURL(str), ["="], []);
        string msg0 = llList2String(bodylist, 0);
        string msg1 = llList2String(bodylist, 1);
        string msg2 = llList2String(bodylist, 2);
        if (msg0 == apiname && msg1 == "LIGHTS" && msg2 == "MENU") {
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
            llDialog(owner, "Please choose a option\nAuto on at dust is set to " + autostate + "\n Intensity set to " + intensitystate, ["Bright", "Med", "Low", "On", "Off", "Auto", "CasperLet", "GridRent", "RESET", "EXIT"], localchan);
        }
    }

    listen(integer c, string n, key id, string msg)
    {
        if (c == localchan && id == owner) {
            string sendapimsg;
            if (msg == "Bright") {
                intensity = 1.0;
                lightchanger(lightingstate);
                sendapimsg = apiname+"=LIGHTS=bright";
            }
            if (msg == "Med") {
                intensity = 0.5;
                lightchanger(lightingstate);
                sendapimsg = apiname+"=LIGHTS=med";
            }
            if (msg == "Low") {
                intensity = 0.1;
                lightchanger(lightingstate);
                sendapimsg = apiname+"=LIGHTS=low";
            }
            if (msg == "On") {
                lightchanger("on");
                autoon = FALSE;
                llSetTimerEvent(0.0);
                sendapimsg = apiname+"=LIGHTS=on";
            }
            if (msg == "Off") {
                lightchanger("off");
                autoon = FALSE;
                llSetTimerEvent(0.0);
                sendapimsg = apiname+"=LIGHTS=off";
            }
            if (msg == "Auto") {
                sendapimsg = apiname+"=LIGHTS=auto";
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
                sendapimsg = apiname+"=LIGHTS=RESET";
                llResetScript();
            }
            if (msg == "CasperLet") {
                waitingforping = TRUE;
                llInstantMessage(owner, "Please touch the CasperLet rental box/meter you like to use with this Light System.");
            }
            llRegionSay(apichan, sendapimsg);
            llMessageLinked(LINK_SET, 0, sendapimsg, "");
            llListenRemove(gListener);
        }else if (c == casperletchan) {
            if (llGetOwnerKey(id)==llGetOwner() && id != llGetOwner()) {
                list tmp = llParseString2List(msg,["|"],[]);
                list tmp2 = llParseString2List(msg,["@"],[]);
                if (llList2String(tmp2,0)=="ADDTNTS") {
                    if (id == listeningunit) {
                        tmp2 = llParseString2List(llList2String(tmp2,1),["#"],[]);
                        additionaltenants=[];    
                        integer x;
                        for(x=0; x<llGetListLength(tmp2); x=x+2) {
                            additionaltenants+=[llList2Key(tmp2, x)];   
                        }
                    }   
                }
                if (llList2String(tmp,0)=="REXTR") {
                    if (waitingforping) {
                        waitingforping = FALSE;
                        candidate = id;
                        llParticleSystem([7, 6.0, 1, <1,1,1>, 3, <0,0,1>, 2, 1.0, 4, 0.5, 5, <0.07, 0.07, 0.1>, 6, <0.1,0.1,0.1>, 13, 0.01, 15, 2, 16, 0.1, 17, 3.0, 18, 3.0, 8, <0,0,-0.4>, 22, 0.0, 23, PI, 21, <0,0,1>, 19, 0.0, 0, PSYS_PART_EMISSIVE_MASK | PSYS_PART_FOLLOW_SRC_MASK | PSYS_PART_FOLLOW_VELOCITY_MASK | PSYS_PART_TARGET_POS_MASK | PSYS_PART_TARGET_LINEAR_MASK, 9, PSYS_SRC_PATTERN_DROP, 20, id, 12, "chain"]);
                        llDialog(llGetOwner(),"Please watch the particles emitting from the sign. Are they going to the correct unit?",["YES","NO"],77777); 
                    }else if (id == listeningunit) {
                        key user = llList2Key(tmp,2);
                        if (user != NULL_KEY) {
                            if (owner != user) {
                                tenant = user;
                                llInstantMessage(tenant,"Hey, thanks for renting with us! If you want, you can now touch the sign above your store and drop your own texture into it!");
                            }
                        }else {
                            additionaltenants=[];
                        }
                    }   
                }
            }else if (id == llGetOwner() && candidate != "") {
                llParticleSystem([]);
                if (message == "YES") {    
                   llRegionSay(-77777, "CLAPI"+(string)candidate+"-Reset|"+(string)owner+"|0");
                   listeningunit = candidate; 
                }
                candidate = "";
            } 
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