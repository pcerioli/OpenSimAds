integer touchedtime;
integer holdtimercountdown = 30;
integer randnumber;
integer localchan;
integer gListener;
float resettimer = 10.0;
key owner;
key primkey;
key toucher = NULL_KEY;
integer face = ALL_SIDES;
integer price = 1000;
float tokenworth = 0.01;
float volume = 1.0;
string touchedsound = "67cc2844-00f3-2b3c-b991-6418d01e1bb7";
string claimsound = "77a018af-098e-c037-51a6-178f05877c6f";
list particlelist = [PSYS_PART_FLAGS, PSYS_PART_WIND_MASK | PSYS_PART_EMISSIVE_MASK, PSYS_SRC_PATTERN, PSYS_SRC_PATTERN_EXPLODE, PSYS_SRC_TEXTURE, "dollarsign",PSYS_PART_START_GLOW, 0.5];
list make_ordered_buttons(integer input)
{
    string output = "0, Exit, Unknown, 7, 8, 9, 4, 5, 6, 1, 2, 3";
    return llCSV2List(output);
}
default
{
    state_entry()
    {
        owner = llGetOwner();
        llSetPayPrice(PAY_HIDE, [PAY_HIDE, PAY_HIDE, PAY_HIDE, PAY_HIDE]);
        state ready;
    }
}
state ready
{
    state_entry()
    {
        llSetText("", <0.0,0.0,0.0>, 0.0);
        llTargetOmega(<0.0,0.0,0.1>,TWO_PI,0.2);
        primkey = llGetKey();
        localchan = (integer)("0x80000000"+llGetSubString((string)primkey,-8,-1));
        llSetAlpha(1.0, face);
        llSetPrimitiveParams([PRIM_PHANTOM, TRUE]);
    }
    touch_end(integer n)
    {
        toucher = llDetectedKey(0);
        state touched;
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
        llPlaySound(touchedsound, volume);
    }
    timer()
    {
        integer remainingtime = touchedtime - llGetUnixTime();
        llSetText("Token is being claimed by "+llKey2Name(toucher)+"\nRemaining Time: "+(string)remainingtime+" seconds.", <1.0,1.0,1.0>, 1.0);
        if (remainingtime == 0) {
            state claiming;
        }
    }
}
state claiming
{
    state_entry()
    {
        llSetTimerEvent(0.0);
        gListener = llListen(localchan, "", toucher, "");
        randnumber = (integer)llFrand(9.0);
        string diamsg = "\n"+(string)randnumber+"\n
        You have 30 seconds to pick the correct number that is shown above.";
        llDialog(toucher, diamsg, make_ordered_buttons(9), localchan);
        llSetPrimitiveParams([PRIM_PHANTOM, TRUE]);
        llSetText(llKey2Name(toucher)+" is claiming this token", <0.0,1.0,0.0>, 1.0);
        llSetTimerEvent(30.0);
    }
    listen(integer chan, string name, key id, string msg)
    {
        if (id == toucher) {
            if (msg == (string)randnumber) {
                llParticleSystem(particlelist);
                llInstantMessage(toucher, "Correct!");
                llSleep(10.0);
                llPlaySound(claimsound, volume);
                state resttoken;
            }else if (msg != (string)randnumber) {
                toucher = NULL_KEY;
                llSay(0, "Incorrect number. Please try again in "+(string)llRound(resettimer)+" seconds.");
                state resttoken;
            }
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
        llSetTimerEvent(0.0);
        llSetText("", <0.0,0.0,0.0>, 0.0);
        toucher = NULL_KEY;
        llSetAlpha(0.0, face);
        llSetTimerEvent(resettimer);
        llTargetOmega(<0.0,0.0,0.0>,0.0,0.0);
        llSetPrimitiveParams([PRIM_PHANTOM, TRUE]);
        llParticleSystem([]);
    }
    timer()
    {
        state ready;
    }
}