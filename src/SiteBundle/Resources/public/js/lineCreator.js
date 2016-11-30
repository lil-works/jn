

var lineCreator = {
    init:function(){


        Tone.Transport.timeSignature = [4, 4];
        Tone.Transport.bpm.value = 180;

        //L/R channel merging
        var merge = new Tone.Merge();

        //a little reverb
        var reverb = new Tone.Freeverb({
            "roomSize" : 0.2,
            "wet" : 0.3
        });

        merge.chain(reverb, Tone.Master);

        //the synth settings
        var synthSettings = {
            "oscillator": {
                "detune": 0,
                "type": "custom",
                "partials" : [2, 1, 2, 2],
                "phase": 0,
                "volume": 0
            },
            "envelope": {
                "attack": 0.005,
                "decay": 0.3,
                "sustain": 0.2,
                "release": 1,
            },
            "portamento": 0.01,
            "volume": 1
        };

        //left and right synthesizers
        var synthL = new Tone.Synth(synthSettings).connect(merge.left);
        var synthR = new Tone.Synth(synthSettings).connect(merge.right);





        var intervals = [
            0,
            -1,1,
            -2,2,
           -3,3, // -3
            -4,4, // 3
          -5,5, //4
            -6,6, //#4 b5
            -7,7 // 5
            -8,8 // b6
            -9,9 // 6
            -10,10 // b7
            -11,11 // 7
            -12,12 //octave
            -13,13 //b9
            -14,14 //9


        ];
        var steps = 4;
        var toneEvents = [];


        console.log(
            this.digitToToneOctave(40),
            this.digitToToneOctave(41)
        );

        toneEvents.push([40]);
        toneEvents.push([32]);
        toneEvents.push([48]);
        toneEvents.push([41]);

        toneEvents.push([40]);
        toneEvents.push([32]);
        toneEvents.push([48]);
        toneEvents.push([41]);

        toneEvents.push([40]);
        toneEvents.push([32]);
        toneEvents.push([48]);
        toneEvents.push([41]);

        toneEvents.push([40]);
        toneEvents.push([32]);
        toneEvents.push([48]);
        toneEvents.push([41]);

        toneEvents.push([40]);
        toneEvents.push([32]);
        toneEvents.push([48]);
        toneEvents.push([41]);

        toneEvents.push([40]);
        toneEvents.push([32]);
        toneEvents.push([39]);
        toneEvents.push([41]);
        toneEvents.push([40]);
        toneEvents.push([32]);
        toneEvents.push([43]);
        toneEvents.push([41]);
        toneEvents.push([40]);
        toneEvents.push([32]);
        toneEvents.push([51]);
        toneEvents.push([41]);







        var pathToFind = [];

        for(i=0;i<toneEvents.length;i++){
            if(i<toneEvents.length - 1){
                   pathToFind.push([ toneEvents[i]  ,toneEvents[i+1] ]);
            }
        }

        var fromTo = [];
        for(i=0;i<pathToFind.length;i++){
            fromTo[i] = [];
            var combs = this.createFromToCombination( pathToFind[i][0],pathToFind[i][1])
            for(j=0;j<combs.length;j++) {
                var delta = combs[j][1] - combs[j][0];
                fromTo[i].push( [combs[j] , delta]);
            }
        }





        var intervalCombinations = this.getIntervalCombinations(steps,intervals);

        var matchingSequence = [];
        for( var i = 0 ; i<intervalCombinations.length;i++){
            var tot = 0;
            $.each(intervalCombinations[i],function(k,v){
                tot+=parseInt(v);

            });

            for(j=0;j<fromTo.length;j++){
                if(!matchingSequence[j])
                    matchingSequence[j] = [];
                for(k=0;k<fromTo[j].length;k++){
                    if(fromTo[j][k][1] == tot){
                        var seqTone = this.getSequenceTones(fromTo[j][k][0][0],intervalCombinations[i]);

                        matchingSequence[j].push(seqTone);




                    }

                }
            }

        }

        //$("#stop").click(function(){Tone.Transport.stop();});

        console.log(matchingSequence);



    },
    setButton:function(element,seqTone,synthL,synthR){
        $('#'+element).click(function(){
            //the two Tone.Sequences
            var partL = new Tone.Sequence(function(time, note){
                synthL.triggerAttackRelease(note, "8n", time);
            }, seqTone, "8n").start();

            var partR = new Tone.Sequence(function(time, note){
                synthR.triggerAttackRelease(note, "8n", time);
            }, seqTone, "8n").start("1m");

            //set the playback rate of the right part to be slightly slower
           // partR.playbackRate = 0.985;

            Tone.Transport.start("+0.1");
            console.log(seqTone)
        });
    },
    getSequenceTones:function(startTone,intervalCombination){
        var current = startTone;
        var o = [];
        o.push(this.digitToToneOctave(startTone));
        for(l=0;l<intervalCombination.length;l++){

            o.push(this.digitToToneOctave(current+intervalCombination[l]));
            current+=intervalCombination[l];
        }
        return o;
    },
    getIntervalCombinations:function(steps,intervals){

        var arraysToCombine = [];
        for (var i = 0; i < steps; i++) {
            arraysToCombine[i]=intervals;
        }

        return this.getAllCombinations(arraysToCombine);
    },
    getAllCombinations:function(arraysToCombine) {
        var divisors = [];
        for (var i = arraysToCombine.length - 1; i >= 0; i--) {
            divisors[i] = divisors[i + 1] ? divisors[i + 1] * arraysToCombine[i + 1].length : 1;
        }

        function getPermutation(n, arraysToCombine) {
            var result = [],
                curArray;
            for (var i = 0; i < arraysToCombine.length; i++) {
                curArray = arraysToCombine[i];
                result.push(curArray[Math.floor(n / divisors[i]) % curArray.length]);
            }
            return result;
        }

        var numPerms = arraysToCombine[0].length;
        for(var i = 1; i < arraysToCombine.length; i++) {
            numPerms *= arraysToCombine[i].length;
        }

        var combinations = [];
        for(var i = 0; i < numPerms; i++) {
            combinations.push(getPermutation(i, arraysToCombine));
        }
        return combinations;
    },

    createFromToCombination:function(from,to){
        var o =[];
        $.each(from, function( iFrom, vFrom ) {
            $.each(to, function( iTo, vTo ) {
                o.push([vFrom,vTo]);
            });
        });
        return o;
    },

    digitToToneOctave:function(digit){
        var octave = parseInt(digit /12) ;
        var tones = ["C","Db","D","Eb","E","F","Gb","G","Ab","A","Bb","B"];
        return tones[digit%12] + octave;
    }

};