window.addEventListener('load', function(){
  reloadr['reload'] = function () {
    location.reload();
  }
  var source = new EventSource(reloadr.reloadSource);
  source.addEventListener('message', function(e){
    console.log(e.data);
    if(e.data.indexOf('reloadr: ') > -1) {
      var method = e.data.split('reloadr: ')[1];
      reloadr[method]();
    }
  }, false)
}, false);