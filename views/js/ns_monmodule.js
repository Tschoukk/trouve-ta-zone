var map = null;
var locations = [
  ['75001',48.86,2.33],
  ['75002',48.86,2.34],
  ['75003',48.86,2.36],
  ['75004',48.85,2.35],
  ['75005',48.84,2.35],
  ['75006',48.84,2.33],
  ['75007',48.85,2.32],
  ['75008',48.87,2.31],
  ['75009',48.87,2.34],
  ['75010',48.87,2.36],
  ['75011',48.85,2.38],
  ['75012',48.84,2.38],
  ['75013',48.83,2.36],
  ['75014',48.83,2.32],
  ['75015',48.84,2.30],
  ['75016',48.86,2.28],
  ['75017',48.88,2.32],
  ['75018',48.89,2.34],
  ['75019',48.89,2.37],
  ['75020',48.87,2.39],
];

$(document).ready(function(){
  $('.menu-tab').click(function(){
    $('.menu-hide').toggleClass('show');
    $('.menu-tab').toggleClass('active');
    $('.menu-tab').hide();
  });
  $('.menu-tab').hide();  
});

function initMenu() {
  $('.toggle').on("click", (function(e) {
    e.preventDefault();
  
    var $this = $(this);
  
    if ($this.next().hasClass('show')) {
        $this.next().removeClass('show');
        $this.next().slideUp(350);
    } else {
        $this.parent().parent().find('li .inner').removeClass('show');
        $this.parent().parent().find('li .inner').slideUp(350);
        $this.next().toggleClass('show');
        $this.next().slideToggle(350);
    }
  }));
}

function clearMenu() {
  $(".accordion").empty()
}

function initialize() {
  map = L.map('map').setView([48.86, 2.33], 11);
  mapLink =
    '<a href="http://openstreetmap.org">OpenStreetMap</a>';
  L.tileLayer(
    'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; ' + mapLink + ' Contributors',
      maxZoom: 18,
    }).addTo(map);

  var myFeatureGroup = L.featureGroup().addTo(map).on("click", groupClick);

  for (var i = 0; i < locations.length; i++) {
    marker = new L.marker([locations[i][1], locations[i][2]])
      .addTo(myFeatureGroup); 
    marker.zone = locations[i][0];       
  }
}

function groupClick(event) {
  console.log(event.layer.zone);
  clearMenu();
  var zone = event.layer.zone;
  var query = $.ajax({
    type: 'POST',
    url: 'modules/ns_monmodule/controllers/front/ajax.php',
    data: 'method=myMethod&data=' + zone,
    dataType: 'json',
    success: function(jsonData) {
      console.log(jsonData); // this is null!?
      var list = {};
      $('.zone').empty().append(zone);
      for(data of jsonData.result){
        if(!list[data.categories]) {
          list[data.categories] = [];
        }
        list[data.categories].push({name: data.name, link: data.meta_description});               
      }
      var categories = Object.keys(list);
      for(idx in categories){
        $(".accordion").append( $( '<li class="category"><a class="toggle" href="javascript:void(0);">'+ categories[idx] +'</a><ul class="inner" id="category'+ idx +'"></ul></li>' ) );
        for (item of list[categories[idx]]) {
          $("#category" + idx).append( $( '<li><a class="item" href="'+ item.link +'" target="_blank">'+ item.name +'</a></li>' ) );
        }        
      }
      initMenu();
      $('.menu-tab').show();
    }
  });
  $('.menu-tab').click();
}

initialize();