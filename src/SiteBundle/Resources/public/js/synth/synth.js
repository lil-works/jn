var JNSYNTH = {


    init:function(){

    },

    setSynth:function(instrument,mode){

        if(JNSYNTH.synth)
            JNSYNTH.synth.dispose();


        if(!instrument)
            var instrument = "piano";

        JNSYNTH.initialized = true;

        if(mode == "poly"){
            var polySynth = new Tone.PolySynth(4, Tone.Synth).toMaster();
            JNSYNTH.synth = polySynth ;
        }else{
            var synth = new Tone.Synth(
                this.instruments[instrument]
            ).toMaster();
            JNSYNTH.synth = synth ;
        }

        return this;
    },



    instruments:{
        triangle:{
            oscillator:{
                type:"triangle"
            },
            envelope:{
                attack:0.1,
                decay:0.2,
                sustain:0.6,
                release:1
            }},
        square:{
            oscillator:{
                type:"square"
            },
            envelope:{
                attack:0.1,
                decay:0.2,
                sustain:0.6,
                release:1
            }},
        sawtooth:{
            oscillator:{
                type:"sawtooth"
            },
            envelope:{
                attack:0.1,
                decay:0.2,
                sustain:0.6,
                release:1
            }},
        sine:{
            oscillator:{
                type:"sine"
            },
            envelope:{
                attack:0.1,
                decay:0.2,
                sustain:0.6,
                release:1
            }}
      },
    synth:null,
    initialized:null

}


