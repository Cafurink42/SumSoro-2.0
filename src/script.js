let $VolumesSalvos = []; //lista vazia 
let $ButtonSaveValue = document.querySelector("#button-done");

$ButtonSaveValue.addEventListener("click", function(){
    let $Result  = document.querySelector("#qtd");
    let $VolumeInserido = parseFloat(document.querySelector("#input_area").value);
    
    if (isNaN($VolumeInserido)){
        $Result.textContent = "Por favor, digite algum valor !"; 
    }else{
        $VolumesSalvos.push($VolumeInserido);
        $Result.textContent = "Volumes salvos: " + $VolumesSalvos;
    }
 
    document.querySelector("#input_area").value = "";
  
});

let $buttonCheckDoneAll = document.querySelector("#button-doneAll");
$buttonCheckDoneAll.addEventListener("click", function(){
    let $sum = 0;
    for (let i = 0; i<$VolumesSalvos.length; i++){
        $sum = $VolumesSalvos[i] + $sum;
    }
    let $Result = document.querySelector("#qtd");
    let $ResultExponential = $sum.toLocaleString('de-DE', {maximumfractionDigits: 2});
    $Result.textContent = "Volumes totais : " +  $ResultExponential;
}); 
