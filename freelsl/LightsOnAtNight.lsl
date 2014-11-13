float intensity = 1.0;
float radius = 20.0;

default
{
    state_entry()
    {
        llSetTimerEvent(30.0);
    }
    
    timer()
    {
        vector sun = llGetSunDirection();
        if(sun.z > 0.0)
        {
            llSetPrimitiveParams([ PRIM_FULLBRIGHT, ALL_SIDES, FALSE,
                            PRIM_GLOW, ALL_SIDES, 0.0,
                            PRIM_POINT_LIGHT, FALSE, <0.0,0.0,0.0>, 0.0, 0.0, 0.75 ]);
            llSetTimerEvent(30.0);
        } else {
            llSetPrimitiveParams([ PRIM_FULLBRIGHT, ALL_SIDES, TRUE,
                            PRIM_GLOW, ALL_SIDES, 10.0,
                            PRIM_POINT_LIGHT, TRUE, <1.0,1.0,1.0>, intensity, radius, 0.75 ]);
            llSetTimerEvent(30.0);
        }
    }
}