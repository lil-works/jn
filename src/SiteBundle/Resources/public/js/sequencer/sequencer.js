/**
 * Created by lil-works on 08/12/16.
 */
var SEQ = {

    init:function(bin){
        this.bin = bin;



        this.createDrumTrack();
        this.createCompTrack();
        this.createBassTrack();
        console.log("SEQINIT",this.tracks);
        return this;
    },


    aggregate:function(a,i){
        var s = a[i]
        for(j=0;j< a.length;j++){
            if(a[i] != a[j] ){

            }
        }
    },

    permArr : [],
    usedChars : [],
    permute :function (input) {

            var i, ch;
            for (i = 0; i < input.length; i++) {
                ch = input.splice(i, 1)[0];
                this.usedChars.push(ch);
                if (input.length == 0) {
                    this.permArr.push(this.usedChars.slice());
                }
                this.permute(input);
                input.splice(i, 0, ch);
                this.usedChars.pop();
            }

            return this.permArr
        },

    createCompTrack:function(){
        /**
         *  PIANO
         */
        var piano = new Tone.PolySynth(4, Tone.Synth, {
            "volume" : -10,
            "oscillator" : {
                "partials" : [1, 2, 1],
            },
            "portamento" : 0.05
        }).toMaster()

        var toneArray = [];
        $.each(this.bin,function(i,v){
            $.each(v,function(k,toneObject){
                toneArray.push(DIGIT.toneForTone(toneObject.wPopulatedName)+4);
            });
        });
        var permutation = this.permute(toneArray);


        var chord1 = permutation[ Math.floor((Math.random() * permutation.length) + 1)].slice(0,3);
        var chord2 = permutation[ Math.floor((Math.random() * permutation.length) + 1)].slice(0,4);
        var chord3 = permutation[ Math.floor((Math.random() * permutation.length) + 1)].slice(0,3);


        var pianoPart = new Tone.Part(function(time, chord){
            piano.triggerAttackRelease(chord, "8n", time);
        }, [["0:0:2", chord1], ["0:1", chord2], ["0:1:3", chord3], ["0:2:2", chord1], ["0:3", chord3], ["0:3:2", chord2],["1:0:1", chord3]]).start();

        pianoPart.loop = true;
        pianoPart.loopEnd = "2m";
        pianoPart.humanize = true;
        this.tracks["comp"] = [pianoPart];
    },
    createBassTrack:function(){

        var bass = new Tone.MonoSynth({
            "volume" : -10,
            "envelope" : {
                "attack" : 0.2,
                "decay" : 0.6,
                "release" : 4
            },
            "filterEnvelope" : {
                "attack" : 0.001,
                "decay" : 0.01,
                "sustain" : 0.5,
                "baseFrequency" : 200,
                "octaves" : 2.6
            }
        }).toMaster();

        var bassPart = new Tone.Sequence(function(time, note){
            bass.triggerAttackRelease(note, "16n", time);
        }, [ ["C2",null,null,"G2"], ["G2",null,null,"C2"], "C2", ["E2", null , "G2","G3"] ]).start(0);

        bassPart.probability = 0.9;
        this.tracks["drum"] = [bassPart];
    },
    createDrumTrack:function(){

        var kick = new Tone.MembraneSynth({
            "envelope" : {
                "sustain" : 0,
                "attack" : 0.02,
                "decay" : 0.8
            },
            "octaves" : 10
        }).toMaster();

        var kickPart = new Tone.Loop(function(time){
            kick.triggerAttackRelease("C2", "8n", time);
        }, "2n").start(0);


        var hithat = new Tone.NoiseSynth({
            "volume" : -20,
            "envelope" : {
                "attack" : 0.0000001,
                "decay" : 0.2,
                "sustain" :.05
            },
            "filterEnvelope" : {
                "attack" : 0.001,
                "decay" : 0.1,
                "sustain" :.1
            }
        }).toMaster();

        var hithatPart = new Tone.Loop(function(time){
            hithat.triggerAttack(time);
            console.log(time);
        }, "16n").start();
        hithatPart.probability = 0.4;

        /*
         SNARE
         */
        var snare = new Tone.NoiseSynth({
            "volume" : -5,
            "envelope" : {
                "attack" : 0.001,
                "decay" : 0.2,
                "sustain" : 0
            },
            "filterEnvelope" : {
                "attack" : 0.001,
                "decay" : 0.1,
                "sustain" : 0
            }
        }).toMaster();

        var snarePart = new Tone.Loop(function(time){
            snare.triggerAttack(time);
        }, "2n").start("4n");

        this.tracks["drum"] = [snarePart,kickPart,hithatPart];
    },

    tracks:[],
    bin:{}
}
