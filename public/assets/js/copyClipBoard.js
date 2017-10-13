var toCopy  = document.getElementById('to-copy');
var btnCopy = document.getElementById('copy');

btnCopy.addEventListener('click', function(){
    toCopy.select();
    document.execCommand('copy');
    return false;
});

