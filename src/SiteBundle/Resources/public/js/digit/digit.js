/**
 * Created by lil-works on 07/12/16.
 */
var DIGIT = {

    toneForTone:function(tone){

        var octave = tone.slice(-1);
        var toneName  = tone.replace(octave,'');

        if(toneName.indexOf('/') != -1){
            var t = toneName.split('/');
            toneName = t[1];
        }
        $.each(this.strangeToneNames,function(k,v){
            if(toneName == v[0]){
                toneName = v[1];
            }
        })

        return toneName+octave;

    },

    strangeToneNames:[
        ['Eb','D#'],
        ['E#','F'],
        ['B#','C'],
        ['C##','D'],
        ['D##','E'],
        ['E##','Gb'],
        ['F##','G'],
        ['G##','A'],
        ['A##','B'],
        ['B##','Db'],
        ['Fb','E'],
        ['Cb','B'],
        ['Cbb','Bb'],
        ['Dbb','C'],
        ['Ebb','D'],
        ['Fbb','Eb'],
        ['Gbb','F'],
        ['Abb','G'],
        ['Bbb','A'],
        ['C#','Db'],
        ['D#','Eb'],
        ['F#','Gb'],
        ['G#','Ab'],
        ['A#','Bb'],
    ]


}