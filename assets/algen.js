function AlgenStart(maxgen,populasi,iter){
  if (maxgen>0){
    if(iter>0){

      var off=OrderCrossover(populasi[Math.floor(Math.random() * 3)],populasi[Math.floor(Math.random() * 3)]);
      var mut=Mutasi(off[Math.floor(Math.random() * 1)]);
      var newpop = RoulleteSelection(populasi,off[0],off[1],mut,data);
      $("#coba").append("Iterasi :"+iter+" Data : ");
      maxgen--;
      console.log(newpop);
      AlgenStart(maxgen,newpop,iter+1);


  //    console.log(populasi);

    }else{
      //console.log('iter :'+iter);
      //console.log(populasi);
      console.log('start');
      GeneratePop(basearray,populasi,);
      AlgenStart(maxgen,populasi,iter+1);
//      $("#newtab").append('<li role="presentation"><a href="#gen'+iter+'" aria-controls="profile" role="tab" data-toggle="tab">'+iter+'</a></li>');
//      var isigen="<div>"+newpop[0]+"</div><div>"+newpop[1]+"</div><div>"+newpop[2]+"</div><div>"+newpop[3]+"</div><div>"+newpop[4]+"</div><div>"+newpop[5]+"</div>";
//      $("#newcontent").append('<div role="tabpanel" class="tab-pane " id="gen'+iter+'">'+isigen+'</div>')
    }
  }else{
  console.log('END, Maxgen :'+maxgen);
  }
}
var basearray = [ 'jogjakarta', 'jakarta','bandung', 'surabaya'];
function testing(){

  var populasi=[];
  GeneratePop(basearray,populasi);
  console.log(populasi);
  var off =OrderCrossover(populasi[Math.floor(Math.random() * 5)],populasi[Math.floor(Math.random() * 5)]);
  console.log(off);
  var rand = Math.floor(Math.random() * 1);

  var mut = Mutasi(off[Math.floor(Math.random() * 1)]);
  console.log(mut);
}

function Shuffle(ar) {
    var j, x, i;
    var newar=[];
    for(x in ar){
      newar.push(ar[x]);
    }
    for (i = ar.length - 1; i > 0; i--) {
        j = Math.floor(Math.random() * (i + 1));

        x = newar[i];
        newar[i] = newar[j];
        newar[j] = x;
        return newar;
    }
};
function Mutasi(ar){
  var i,j,x;
  var newar=[];
  for (var r=0;r<=ar.length-1;r++){
    newar[r]=ar[r];
  }

  j = Math.floor(Math.random() * (ar.length-1));
  i = Math.floor(Math.random() * (ar.length-1));
  console.log(i+" "+j);
  x = newar[i];
  newar[i] = newar[j];
  newar[j] = x;
  return newar;
}
function OrderCrossover (par1,par2){
  var i,j,subBound1,subBound2,x;
  var off1=[];
  var off2=[];
  i = Math.floor(Math.random() * (par1.length));
  j = Math.floor(Math.random() * (par1.length));
  if (i!=j){
    if (i>j) {
      subBound2=i;
      subBound1=j;
    }else if (i<j){
      subBound1=i;
      subBound2=j;
    }
  }else{
    subBound1=i;
    subBound2=j;
  }

  for(i=0;i<=par1.length-1;i++){
    if (i>=subBound1 && i<= subBound2){
      off1[i]=par1[i];
      off2[i]=par2[i];
    }else{
      off1[i]='';
      off2[i]='';
    }
  }

  if (off1.includes("")) {
    FillArray(off1,par2,subBound2+1,subBound2+1);

  }
  if (off2.includes("")) {
    FillArray(off2,par1,subBound2+1,subBound2+1);

  }
  return [off1,off2];
}

function FillArray(off,par,startoff,startpar){
    startoff=startoff%off.length;
    startpar=startpar%off.length;
    if(off.includes("")){
      if(off.includes(par[startpar])){
        FillArray(off,par,startoff,startpar+1);
      }else{
        off[startoff]=par[startpar];
        FillArray(off,par,startoff+1,startpar+1);
      }
    }else{
      return off;
    }
}


function TotalJarak(dafjarak,ar){
return  dafjarak.semarang[ar[0]]+dafjarak[ar[0]][ar[1]]+dafjarak[ar[1]][ar[2]]+dafjarak[ar[2]][ar[3]]+dafjarak[ar[3]]["semarang"];
}

function Normalisasi(ar){
  var kum=0;
  var weightplus=0;
  //console.log(ar);
  for (var i = 0; i < ar.length; i++) {
    kum =kum + ar[i]['fitness'];
  }
  for (var i = 0; i < ar.length; i++) {
    ar[i]['weight']=(ar[i]['fitness']/kum)+weightplus;
    weightplus=ar[i]['weight'];
  }
//console.log(ar);
  return ar;
}

function RoulleteSelection(pop,off1,off2,mut,dafjarak){
  var select=[];
  var newpop=[];
  select[0]={ "fitness" : 1/TotalJarak(dafjarak,pop[0]) };
  select[1]={ "fitness" : 1/TotalJarak(dafjarak,pop[1]) };
  select[2]={ "fitness" : 1/TotalJarak(dafjarak,pop[2]) };
  select[3]={ "fitness" : 1/TotalJarak(dafjarak,pop[3]) };
  select[4]={ "fitness" : 1/TotalJarak(dafjarak,pop[4]) };
  select[5]={ "fitness" : 1/TotalJarak(dafjarak,pop[5]) };
  select[6]={ "fitness" : 1/TotalJarak(dafjarak,off1) };
  select[7]={ "fitness" : 1/TotalJarak(dafjarak,off2) };
  select[8]={ "fitness" : 1/TotalJarak(dafjarak,mut) };
  select[0]['array']=pop[0];
  select[1]['array']=pop[1];
  select[2]['array']=pop[2];
  select[3]['array']=pop[3];
  select[4]['array']=pop[4];
  select[5]['array']=pop[5];
  select[6]['array']=off1;
  select[7]['array']=off2;
  select[8]['array']=mut;
  Normalisasi(select);
  for (var i = 0; i <=6; i++) {
  //  console.log(select[i]["array"]);
    newpop[i]=Terpilih(select,Math.random());
  }
  return newpop;
}

function Terpilih(ar,rand){
  var newpop;
  var key=0;
  //console.log(rand);
  for(var i=0;i<=ar.length-1;i++){
    if(ar[i]['weigth']<rand){
      if(ar[i]['weight']>ar[key]['weight']){
        key=i;
      }
    }
  }
  newpop=ar[key]["array"];
  return newpop;
}

function GeneratePop(ar,res){
  for (var i = 0; i <= 5; i++) {
      res.push(Shuffle(ar));
  }
}
