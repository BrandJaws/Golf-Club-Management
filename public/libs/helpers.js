/**
 * 
 * @param array targetArray
 * @param array newArray
 * @returns null
 * 
 * Appends an array to the end of another
 */
function appendArray(targetArray,newArray){
        for(x=0; x<newArray.length; x++){

            targetArray.push(newArray[x]);

        }
}