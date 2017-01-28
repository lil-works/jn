var jnSynth = {

    chordDuration:3,
    scaleDuration:2,
    scaleVelocity:220,
    digit2tones : Array('C','C#','D','D#','E','F','F#','G','G#','A','A#','B'),
    piano : null ,
    init : function(instrumentName){
        if(!instrumentName)
            instrumentName = "piano";
        Synth instanceof AudioSynth; // true
        var testInstance = new AudioSynth;
        testInstance instanceof AudioSynth; // true
        testInstance === Synth; // true
        this.piano = Synth.createInstrument(instrumentName);
    },

    play : function(digits , mode){
        if(!$.isArray(digits)){
            digits = digits.split(",");
        }
        digits.sort();
        $.each(digits,function(key,value){
            if(key>0){
                if(digits[key-1]>value){
                    console.log(key,value , digits[key]);
                    if(digits[key-1]-value>12){
                        digits[key]=parseInt(digits[key])+24;
                    }else{
                        digits[key]=parseInt(digits[key])+12;
                    }

                    console.log(key,value,digits[key]);
                }
            }
        });
        if(mode!="scale") {
            for (d = 0; d < digits.length; d++) {
                if (digits[d] > 0) {
                    octave = Math.floor(digits[d] / 12);
                    toneName = this.digit2tones[digits[d] % 12];

                    this.piano.play(toneName, octave, this.chordDuration);
                }
            }
        }else{
            if(this.counter)
                window.clearInterval(this.counter);
                l=0;
                this.o = Array();
                for(d=0;d<digits.length;d++){
                    if(digits[d]>0){
                        this.o[l] = Array( this.digit2tones[digits[d] % 12] , Math.floor(digits[d] / 12) );
                        l++;
                    }
                }
                this.count = 0;
                this.tot=l;
                this.counter = setInterval(this.timer, this.scaleVelocity);
            }

        },
        timer : function () {
            jnSynth.piano.play(jnSynth.o[jnSynth.count][0], jnSynth.o[jnSynth.count][1], jnSynth.scaleDuration);
            if(jnSynth.count<jnSynth.tot-1){
                jnSynth.count++;
            }else{
                window.clearInterval(jnSynth.counter);
            }
        }
}



jnPlay = function(mode,digits){



    for(d=0;d<digits.length;d++){
        if(digits[d]>0){
            jnPlayFreq(jnGetFreq(digits[d]));
        }
    }

}
/*
jnPlay = function(mode,digits){
    if(clearAllSounds()){
        if(mode == "chord")
            jnPlayChord(digits);
        else
            jnPlayScale(digits);
    }
}
*/
jnPlayTone = function(digit){
    window['s_'+digit] = new Audio("/sounds/bass/"+digit+".ogg");
    window['s_'+digit].play();
}

jnPlayChord = function(digits){
    for(d=0;d<digits.length;d++){
        if(digits[d]>0){
            window['s_'+d] = new Audio("/sounds/bass/"+digits[d]+".ogg");
            window['s_'+d].play();
        }
    }
}

jnPlayScale = function(digits){
    digits.sort();
    l=0;
    for(d=0;d<digits.length;d++){
        if(digits[d]>0){
            window['s_'+d] = new Audio("/sounds/bass/"+digits[d]+".ogg");
            l++;
        }
    }

    var count = 0;
    var tot=l;

    var counter = setInterval(timer, 150);
    function timer() {
        window['s_'+count].play();
        if(count<tot-1){
            count++;
        }else{
            window.clearInterval(counter);
        }

    }

}

clearAllSounds = function(){
    for(var s in window){
        if(s.search("s_") == 0 && window[s]){
            //window[s].currentTime = 0;
            window[s].pause();
        }
    }
    return true;
}

jnGetFreq = function(n){
    return 440*Math.pow(2,(n-49)/12);
}

jnPlayFreq = function(f){
    context = new AudioContext;
    oscillator = context.createOscillator();
    oscillator.type = 'square';
    oscillator.frequency.value = f;
    oscillator.connect(context.destination);
    oscillator.start(0);
}


$(document).ready(function(){
    jnSynth.init();
    console.log("DOCUMENT READY jnSynth.init()")
});
