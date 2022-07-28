<!DOCTYPE HTML>
<html lang="pl">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=egde,chrome=1" />
<title>JS3</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="styles/style.css" rel="stylesheet" type="text/css">
<link rel="icon" type="image/png" href="axe1.png" />
</head>
<body>
   
    <h1>JS3<img src="axe1.png"></img></h1>
    
    <div id="game">
    <canvas class="game" id="gra" width="800px" height=" 650px"></canvas>
    </div>
    <p id='info'></p>
    <p>Uwaga jeśli <b>pojawią się błąd gry</b> - że przeciwnika nie widać (pracujemy nad rozwiązaniem problemu)<b> Prosimy o odświeżenie strony!</b></p>
    <p>Uwaga Aby zacząć prosimy naćisnąć spację!!!</p>
    <p><b>Lini Kodu: 2144</b></p>
    <div id="a1">
            <a href="wiki.html">Wiki</a><br><br><br>
             <a id='klawisz1' href="klawiszologia.html">INSTRUKCJA - JAK GRAĆ?</a>
    </div>
    <p id="showing"></p>
</body>
<script>
    var INFO = document.getElementById('info');
    var PSHOWING = document.getElementById('showing');
    var Version = '1.0.2';
    var Beta = 'Tak';
    var BeforeAdd = "graphisc of enemy";
    INFO.innerHTML = "<b>" + " Wersja:" + Version + " Beta:"+Beta+" "+"</b>";
    console.log('Wersja: '+Version+" "+"Beta: "+Beta+" "+"Ostatnio dodane:"+BeforeAdd);
    //ZMIENNE-------------------------------
    var can = document.getElementById("gra");
    var ctx = can.getContext('2d');
    var dmg = 10, x = 500, y = 500, szer = 50,wys=65, dx = 0, dy = 0,hp = 60,maxhp=60,defendHP=5,defendBREAK=1,defendChance=1,speed=6;
    var rollingenergy = 1;
    var maxrollingenergy = 2;
    var direction1 = "none";
    var direction2 = "none";
    var rollingNumber = 20;
    var playerGold = 16;
    var kills = 0;
    var toShop = 0;
    var toWaitArena = 0;
    var Items = [];
    var see_Invetory = false;
    var InvetoryTab = "items";
    var menu = "start";
    var PossibleBossFight = false;
    var seeStatisticsTab = false;
    
    //WanderingMerchant
    var WanderingMerchantPasses = false;
    var WanderingMerchantMeter = 0;
    var WanderingMerchantWhenPasses = 1;
    var WanderingMerchantShopSee = false;
    
    var WanderingMerchantHowMuchItems = lenght1;
    var WanderingMerchantItems = [];
    //WanderingMerchant
    
    //ARRAYS
    var Number_Invetory_Basic_Slots = 20;
    var Invetory_Basic_Slots = [];
    var Number_Invetory_Potion_Slots = 12;
    var Invetory_Potion_Slots = [];
    var Number_Quick_Selection_Bar = 4;
    var QuickSelectionBar = [];
    var Invetory_Potion_Slots = [];
    var ItemList = [];
    var ItemEffect = [];
    //ARRAYS
    var SaveHp = false;
    var SabeHpNumber = 0;
    var SuperDefend = false;
    ItemList[0] = new Item("AMULET SIŁY",3,"accessories",1,"Siła +1",false,true,"no",10);
    ItemList[1] = new Item("LECZENIE",   3,"accessories",0,"Za każdego zabitego przeciwnika +3hp", false,true,"no",8);
    ItemList[2] = new Item("SZYBKOŚĆ",   5,"health",    0,"speed+1",true,false,"no");
    ItemList[3] = new Item("ULECZ 8hp",   3,"health",    0,"hp+8",true,false,"no");
    ItemList[4] = new Item("ULECZ 2hp",   1,"potions",    0,"hp+2",false,true,"no");
    ItemList[5] = new Item("ULECZ 2hp",   1,"potions",    0,"hp+2",false,true,"no");
    ItemList[6] = new Item("ULECZ 3hp",   1,"potions",    0,"hp+3",false,true,"no");
    ItemList[7] = new Item("PATELNIA KUCHNI",50,"accessories",4,"SuperDefend==true",false,true,"no",15);
    ItemList[8] = new Item("AMULET ŻYCIA",16,"accessories",2,"+ 10 Max Hp",false,true,"no",15);
    
    
    function ajax_post(msg)
    {
        const hr = new XMLHttpRequest(),url = "php/ajaxmanager.php",textTo = "&command="+msg;

        hr.open("POST", url, true);

        hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        hr.onreadystatechange = function()
        {
            if(hr.readyState == 4 && hr.status == 200) 
            {
                var return_data = hr.responseText;
                PSHOWING.innerHTML = return_data;
            }
        }
    hr.send(textTo);
    }
    ajax_post("ADD");
    
//    Enemy1 = new Enemy(1,"gold",4,8,12,Math.random()*500+20,Math.random()*500+20,50,65,200,200,25);
    //SHOP INVETORY
    var SHOP_IMG = new Image(700,550);
    SHOP_IMG.src = "img/shop/shop.png";
    var INVETORY_IMG = new Image(700,550);
    INVETORY_IMG.src = "img/invetory.png";
    var STATISTICS_IMG = new Image(700,550);
    STATISTICS_IMG.src = "img/statistics.png";
    var WANDERING_MERCHANT_SHOP_IMG = new Image(700,550);
    WANDERING_MERCHANT_SHOP_IMG.src = "img/wanderingmerchamtshop.png";
    var INVETORY_ALCHEMY_IMG = new Image(700,550);
    INVETORY_ALCHEMY_IMG.src = "img/invetorya.png";
    var SHOP_BUTTON_IMG = new Image(200,40);
    SHOP_BUTTON_IMG.src = "img/shop/shopb.png";
    var SHOP_BUY_IMG = new Image(185,30);
    SHOP_BUY_IMG.src = "img/shop/shopbuy.png";
    var SHOP_BUYED_IMG = new Image(185,30);
    SHOP_BUYED_IMG.src = "img/shop/shopbuyed.png";
    //SHOP INVETORY
    
    var POTION_IMG = new Image(32,32);
    POTION_IMG.src = "item/potion.png";
    var ACCESSORIES_IMG = new Image(32,32);
    ACCESSORIES_IMG.src = "item/accessories.png";
    var APPLE_IMG = new Image(32,32);
    APPLE_IMG.src = "item/apple1.png";
    var POTION_0_IMG = new Image(32,32);
    POTION_0_IMG.src = "item/mikstura02.png";
    var IMG_EMERALD = new Image(32,32);
    IMG_EMERALD.src = "item/emerald.png";
    var IMG_KILLS = new Image(32,32);
    IMG_KILLS.src = "item/kills.png";
    var IMG_HEARTH = new Image(32,32);
    IMG_HEARTH.src = "item/live.png";
    
    //ITEM
    var ITEM_POTION_LECZENIE_IMG = new Image(64,64);
    ITEM_POTION_LECZENIE_IMG.src = "item/leczenie.png";
    var ITEM_AMULET_DAMAGE_IMG = new Image(64,64);
    ITEM_AMULET_DAMAGE_IMG.src = "item/1pixelarta.png";
    var ITEM_AMULET_OF_LIFE_IMG = new Image(64,64);
    ITEM_AMULET_OF_LIFE_IMG.src = "item/amuletoflive.png";
    var ITEM_PATELNIA_IMG = new Image(64,64);
    ITEM_PATELNIA_IMG.src = "item/patelnia.png";
    //ITEM
    
    //character
    var CHARACTER_STAY_IMG = new Image(64,64);
    CHARACTER_STAY_IMG.src = "img/character/chstay.png";
    var CHARACTER_LEFT_IMG = new Image(64,64);
    CHARACTER_LEFT_IMG.src = "img/character/chleft.png";
    var CHARACTER_RIGHT_IMG = new Image(64,64);
    CHARACTER_RIGHT_IMG.src = "img/character/chright.png";
    var CHARACTER_UP_IMG = new Image(64,64);
    CHARACTER_UP_IMG.src = "img/character/chup.png";
    var CHARACTER_DEAD_IMG = new Image(64,64);
    CHARACTER_DEAD_IMG.src = "img/character/chdead.png";
    //character
    
    
    //Enemy character
    var ENEMY_CHARACTER_STAY_IMG = new Image(64,64);
    ENEMY_CHARACTER_STAY_IMG.src = "img/Echaracter/ork/chstay2.png";
    var ENEMY_CHARACTER_LEFT_IMG = new Image(64,64);
    ENEMY_CHARACTER_LEFT_IMG.src = "img/Echaracter/ork/chleft2.png";
    var ENEMY_CHARACTER_RIGHT_IMG = new Image(64,64);
    ENEMY_CHARACTER_RIGHT_IMG.src = "img/Echaracter/ork/chright2.png";
    var ENEMY_CHARACTER_RIGHT_2_IMG = new Image(64,64);
    ENEMY_CHARACTER_RIGHT_2_IMG.src = "img/Echaracter/ork/chright3.png";
    var ENEMY_RIGHT_ARRAY = [];
    ENEMY_RIGHT_ARRAY.length = 9;
    var ENEMY_LEFT_ARRAY = [];
    ENEMY_LEFT_ARRAY.length = 9;
    var ENEMY_SLASH_ARRAY = [];
    ENEMY_SLASH_ARRAY.length = 6;
    var SlashAnimation = false;
    
    
    console.group("Loading Times 1");
    console.log("Loading Times 1");
    CheckTheEnemyGraphics("ork");
    Slots();
    console.groupEnd();
    
    Enemy1 = Enemy();
    EISLIVE = false;
//    setTimeout(LoadEnemy1,200);
    var spawnx1;
    var spawny1;
    var LoadedDataEnemy = [];
    var defendChance2;
    var NameOfActualEnemy = "";
//    LoadEnemy1();
    function LoadEnemy(nameOfJsonFile)
    {
    $.ajax({
            type:"GET",
            url:"json/"+nameOfJsonFile,
            success:function(response)
//    Enemy(defendChance,drop,numberofitemsdrop,mindmg,maxdmg,x,y,szer,wys,spawnX,spawnY,hp,dx,dy,objectiveX,objectiveY,state="quest",state2="none",left="none",right="none",down="none",up="none",islive=true)
            {
                LoadedDataEnemy[0] = response.defendChance;
                LoadedDataEnemy[1] = response.drop;
                LoadedDataEnemy[2] = response.numberofitemsdrop;
                LoadedDataEnemy[3] = response.mindmg;
                LoadedDataEnemy[4] = response.maxdmg;
                LoadedDataEnemy[5] = response.spawnX;
                LoadedDataEnemy[6] = response.spawnY;
                LoadedDataEnemy[7] = response.hp;
                LoadedDataEnemy[8] = response.enemyName;
                
                NameOfActualEnemy = LoadedDataEnemy[8];
                defendChance2 = parseInt(LoadedDataEnemy[0]);
                
                CheckTheEnemyGraphics(NameOfActualEnemy);
                Enemy1 = Enemy(LoadedDataEnemy[0],LoadedDataEnemy[1],LoadedDataEnemy[2],LoadedDataEnemy[3],LoadedDataEnemy[4],LoadedDataEnemy[5],LoadedDataEnemy[6],50,65,LoadedDataEnemy[5],LoadedDataEnemy[6],LoadedDataEnemy[7]);
//                Enemy1 = Enemy(response.defendChance,response.drop,response.numberofitemsdrop,response.mindmg,response.maxdmg,response.spawnX,response.spawnY,50,65,response.spawnX,response.spawnY,response.hp); 
                console.log(spawnx1+" "+spawny1);  
//                EY = spawny1;
//                EX = spawnx1;
            }
        });
    }
    
    function CheckTheEnemyGraphics(enemyName)
    {
        if(enemyName=="ork")
        {
            LoadEnemyGraphics("ork");
        }
        else if(enemyName=="skeleton_killer")
        {
            LoadEnemyGraphics("skeleton_killer");
        }
    }
    
    
    function LoadEnemyGraphics(nameOfFolderGraphiscOfEnemy)
    {
        console.time("Load Enemy Graphics");
        for(var xmy = 0; xmy < ENEMY_RIGHT_ARRAY.length; xmy++)
        {
            ENEMY_RIGHT_ARRAY[xmy] = new Image();
            ENEMY_RIGHT_ARRAY[xmy].src = 'img/Echaracter/'+nameOfFolderGraphiscOfEnemy+'/right/'+xmy.toString()+".png";
        }
        for(var xmy = 0; xmy < ENEMY_LEFT_ARRAY.length; xmy++)
        {
            ENEMY_LEFT_ARRAY[xmy] = new Image();
            ENEMY_LEFT_ARRAY[xmy].src = 'img/Echaracter/'+nameOfFolderGraphiscOfEnemy+'/left/'+xmy.toString()+".png";
        }
        for(var xmy = 0; xmy < ENEMY_SLASH_ARRAY.length; xmy++)
        {
        ENEMY_SLASH_ARRAY[xmy] = new Image();
        ENEMY_SLASH_ARRAY[xmy].src = 'img/Echaracter/ork/slash/'+xmy.toString()+".png";
        }
        console.timeEnd("Load Enemy Graphics");
    }
    //Enemy character
    
    var PlayerIMG = CHARACTER_STAY_IMG;
    
    function Interprate(string,InfoMode)
    {
        var StringTable = [];
        var String2 = [];
        var Help1 = [];
        var A1;
        var A2;
        var A3;
        var NumberOfItems = 0;
        
        for(var xmy = 0; xmy < string.length; xmy++)
        {
            StringTable[xmy] = string[xmy];
        }
        for(var xmy = 0; xmy < string.length; xmy++)
        {
            if(StringTable[xmy]=="/")
            {
//                Help1[xmy] = StringTable[xmy];
                A1 = xmy;
                
            }
        }
        for(var xmy = 0; xmy < string.length; xmy++)
        {
            if(StringTable[xmy]=="C")
            {
//                Help1[xmy] = StringTable[xmy];
                NumberOfItems = NumberOfItems + 1;
                
            }
        }
        for(var xmy = 0; xmy < string.length; xmy++)
        {
            if(StringTable[xmy]=="%")
            {
//                Help1[xmy] = StringTable[xmy];
                A2 = xmy;
                
            }
        }
        for(var xmy = 0; xmy < string.length; xmy++)
        {
            if(StringTable[xmy]=="@")
            {
//                Help1[xmy] = StringTable[xmy];
                A3 = xmy;
                
            }
        }
        String2[0] = string.substr(0,A1);
        String2[1] = string.substr(A1+1,A2-1);
        String2[2] = string.substr(A2+1,A2);
        String2[3] = string.substr(A3+1);
        
        if(InfoMode)
        {
            console.log("A3 "+A3);
            console.log("A2 "+A2);
            console.log("A1 "+A1);
            console.log(Help1);
            console.log(StringTable);
        }
        
        console.log(String2);
        console.log(NumberOfItems);
        
    }
    Interprate("C/01%085@02",true);// Item:Id:01 Item:Chancedrop: 82%  Item:NumberOfDrop: 02
//    Interprate("/02%060@01",false);// Item:Id:01 Item:Chancedrop: 82%  Item:NumberOfDrop: 02
    
    function Slots()
    {
        console.time("Slots");
        for(var i = 0; i < Number_Invetory_Basic_Slots; i++)
        {
            Invetory_Basic_Slots[i] = new Slot(i);
            //console.log(Invetory_Basic_Slots);
        }
        for(var x = 0; x < Number_Invetory_Potion_Slots; x++)
        {
            Invetory_Potion_Slots[x] = new Slot(x);
            //console.log(Invetory_Basic_Slots);
        }
        for(var y = 0; y < Number_Quick_Selection_Bar; y++)
        {
            QuickSelectionBar[y] = new Slot(y);
            //console.log(Invetory_Basic_Slots);
        }
        console.timeEnd("Slots");
    }
    //console.log(Invetory_Basic_Slots[5]);
    var ie = 0;
    function AddItemToInvetory(numberOfItem)
    {
        if(Items[numberOfItem].ItemBuy=="yes")
        { 
        if(Items[numberOfItem].ItemIsItemToInvetory&&Items[numberOfItem].ItemType!="potions")
        {
            var IsAdd = false;
            var x = 0;
            for(var x = 0; x < Number_Invetory_Basic_Slots; x++)
            {
                if(Invetory_Basic_Slots[x].SlotContent=="empty")
                {
                    Invetory_Basic_Slots[x].SlotContent = Items[numberOfItem].ItemName;
                    Invetory_Basic_Slots[x].ItemDurabilityWithSlot = Items[numberOfItem].ItemDurability;
                    x = Number_Invetory_Basic_Slots + 20;
//                    console.log(Invetory_Basic_Slots);
                    IsAdd = true;
                    ItemEffect[ie] = Items[numberOfItem].ItemEffect;
                    ie = ie + 1;
//                    console.log(ItemEffect);
                    EffectItemInInvetory();
                    console.table(Invetory_Basic_Slots);
                }
            }
             if(IsAdd==false)
            {
                console.warn("NIE MA MIEJSCA!");
            }
        }
        }
        if(Items[numberOfItem].ItemIsItemToInvetory&&Items[numberOfItem].ItemType=="potions")
        {
            console.log("Dodawanie Mikstury...");
            var Added = false;
            for(var b = 0; b < Number_Invetory_Potion_Slots; b++)
            {
                if(Invetory_Potion_Slots[b].SlotContent=="empty")
                {
                    Invetory_Potion_Slots[b].SlotContent = Items[numberOfItem].ItemName;
                    b = Number_Invetory_Potion_Slots * 2;
                    Added = true;
                    ItemEffect[ie] = Items[numberOfItem].ItemEffect;
                    ie = ie + 1;
                    EffectItemInInvetory();
                    console.log("Dodano!");
                }
            }
           
        console.log(Invetory_Potion_Slots); 
        }
    }
    
    function EffectItemInInvetory()
    {
        maxhp = 60;
        dmg = 10;
        SabeHpNumber = 0;
        for(var x = 0; x < ItemEffect.length; x++)
            {
                if(ItemEffect[x]=="Siła +1")
                {
                    dmg = dmg + 1;
//                    console.log(dmg);
                }
                else if(ItemEffect[x]=="Za każdego zabitego przeciwnika +3hp")
                {
                    SaveHp = true;
                    SabeHpNumber = SabeHpNumber + 3;
                }
                else if(ItemEffect[x]=="SuperDefend==true")
                {
                    SuperDefend = true;
                }
                else if(ItemEffect[x]=="+ 10 Max Hp")
                {
                    maxhp = maxhp + 10;
                }
            }
    }
    
    function Slot(id,durabilityWithSlot=-1,content="empty")
    {
        this.SlotId = id;
        this.SlotContent = content;
        this.ItemDurabilityWithSlot = durabilityWithSlot;
    }
    
    //ZMIENNE-------------------------------
    //ENEMY--------------------------------
//    var Enemy1 = Enemy(1,"gold",4,8,12,80,80,50,65,80,80,23);
    
    function reloadrollingenergy()
    {
        if(rollingenergy==0||rollingenergy!=maxrollingenergy)
        {
            if(maxrollingenergy==rollingenergy)
            {   
                
            }
            else
            {
                rollingenergy++;
            }
            
        }
    }
    
    function GenerateRandomEnemyJson()
    {
        var random = Math.floor(Math.random()*3+1);//od 1 do 3
        console.log(random);
        LoadEnemy('enemy'+random+".json");
    }
    
    
    //setInterval(reloadrollingenergy,3000);
    $(document).on('keyup', function(event) 
    {
        let keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '81'&&menu!="defeat"||keycode == '48'&&menu!="defeat")
        {
              ColisionWithPlayer();
		      if(kolizja)
		      {
                  
                var Esaved;
                if(EDEFENDCHANCE!=0)
                {
                for(var i = 0; i < EDEFENDCHANCE; i++) 
                {
                var Echance = Math.floor(Math.random()*4+1);
                var Echance2 = Math.floor(Math.random()*4+1);
                if(Echance==Echance2)
                {
                    hp = hp - 1;
                    Esaved = true;
//                    console.log("DEFEND CHANCE ENEMY");
                }
                else
                {
                    Esaved = false;
                }
                }
                }
                else
                {
                Esaved = false;
                }      
                 if(Esaved==false)
                {
                  EHP = EHP - dmg;  
                }
                  
		      
                Esaved = "none";    
		      }  //console.log(kolizja);
        }
    });
    

    function Collision(x1,y1,szer1,wys1,x2,y2,szer2,wys2)
    {
        if(x1 + szer1 < x2||
           x2 + szer2 < x1||
           y1  + wys1 < y2||
           y2 + wys2 < y1)
        {
            return false;    
        }
        else
        {
            return true;
        }
    }
          
        
    $(document).on('keyup', function(event) 
    {
        let keycode = (event.keyCode ? event.keyCode : event.which);
        if(menu=='waitarena' && keycode == '32' && see_Invetory==false && ShopVisited==false || menu=='waitarena' && keycode == '57' && see_Invetory==false && ShopVisited==false)
        {
            if(Collision(x,y,szer,wys,ShopX,ShopY,ShopSzer,ShopWys))
            {
                Shop();
                ShopVisited = true;
            }
        }
        
        
        if(menu=='waitarena' && keycode == '32' && see_Invetory==false || menu=='waitarena' && keycode == '57' && see_Invetory==false)
        {
            if(Collision(x,y,szer,wys,ToBossX,ToBossY,ToBossSzer,ToBossWys) && PossibleBossFight)
            {
                Shop();
                console.log("AA!");
//                ShopVisited = true;
                
            }
        }
        if(keycode=='8'&&MovingPotion&&menu=='waitarena'&& see_Invetory==true && InvetoryTab=="alchemy"&& MovingPotion)
        {
            if(QuickSelectionBar[IdFromQuikPotions].SlotContent!="empty")
            {
            var IsAdd = false;
            var x = 0;
            for(var x = 0; x < Number_Invetory_Potion_Slots; x++)
            {
                if(Invetory_Potion_Slots[x].SlotContent=="empty")
                {
                    Invetory_Potion_Slots[x].SlotContent = ItemMemory;
//                    Invetory_Basic_Slots[x].ItemDurabilityWithSlot = Items[numberOfItem].ItemDurability;
                    x = Number_Invetory_Potion_Slots + 20;
//                    console.log(Invetory_Basic_Slots);
                    IsAdd = true;
//                    ItemEffect[ie] = Items[numberOfItem].ItemEffect;
//                    ie = ie + 1;
//                    console.log(ItemEffect);
//                    EffectItemInInvetory();
                    console.table(Invetory_Potion_Slots);
                    MovingPotion = false;
                    ItemMemory = "empty";
                    QuickSelectionBar[IdFromQuikPotions].SlotContent='empty';
                }
            }
             if(IsAdd==false)
            {
                console.warn("NIE MA MIEJSCA!");
            }
            }
            else
            {
                MovingPotion = false;
            }
        }
        if(menu=='waitarena' && keycode == '49' && see_Invetory==true && InvetoryTab=="alchemy"&& MovingPotion&&QuickSelectionBar[0].SlotContent=="empty")
            {
                QuickSelectionBar[0].SlotContent = ItemMemory;
                ItemMemory = "empty";
                Invetory_Potion_Slots[IdFromPotions].SlotContent="empty";
                MovingPotion = false;
            }
        else if(menu=='waitarena' && keycode == '50' && see_Invetory==true && InvetoryTab=="alchemy"&& MovingPotion&&QuickSelectionBar[1].SlotContent=="empty")
            {
                QuickSelectionBar[1].SlotContent = ItemMemory;
                ItemMemory = "empty";
                Invetory_Potion_Slots[IdFromPotions].SlotContent="empty";
                MovingPotion = false;
            }
        else if(menu=='waitarena' && keycode == '51' && see_Invetory==true && InvetoryTab=="alchemy"&& MovingPotion&&QuickSelectionBar[2].SlotContent=="empty")
            {
                QuickSelectionBar[2].SlotContent = ItemMemory;
                ItemMemory = "empty";
                Invetory_Potion_Slots[IdFromPotions].SlotContent="empty";
                MovingPotion = false;
            }
        else if(menu=='waitarena' && keycode == '52' && see_Invetory==true && InvetoryTab=="alchemy"&& MovingPotion&&QuickSelectionBar[3].SlotContent=="empty")
            {
                QuickSelectionBar[3].SlotContent = ItemMemory;
                ItemMemory = "empty";
                Invetory_Potion_Slots[IdFromPotions].SlotContent="empty";
                MovingPotion = false;
            }
    });
    
    $(document).on('keyup', function(event) 
    {
        let keycode = (event.keyCode ? event.keyCode : event.which);
        if(menu=='waitarena' && keycode == '13')
        {
            menu = "arena";
            ESTATE = 'quest';
            GenerateRandomEnemyJson();
//            LoadEnemy('enemy2.json');
//            Enemy1 = Enemy(LoadedDataEnemy[0],LoadedDataEnemy[1],LoadedDataEnemy[2],LoadedDataEnemy[3],LoadedDataEnemy[4],LoadedDataEnemy[5],LoadedDataEnemy[6],50,65,LoadedDataEnemy[5],LoadedDataEnemy[6],LoadedDataEnemy[7]);
            ShopVisited = false;
            WanderingMerchantPasses = false;
        }
    });
    
    
    $(document).on('keyup', function(event) 
    {
        let keycode = (event.keyCode ? event.keyCode : event.which);
        if(menu=='waitarena' && keycode == '86'&& WanderingMerchantPasses)
        {
//            if(Collision(x,y,szer,wys,ToWanderingMerchantX,ToWanderingMerchantY,ToWanderingMerchantSzer,ToWanderingMerchantWys))
//            {
//                console.log("AA!");
//            }
//            else{ 
//                console.log("BB");
//            }
            if(x + szer < ToWanderingMerchantX||
           ToWanderingMerchantX + ToWanderingMerchantSzer < x||
           y  + wys < ToWanderingMerchantY||
           ToWanderingMerchantY + ToWanderingMerchantWys < y)
        {
                
        }
        else
        {
            console.warn("YOU");
            WanderingMerchantShopSee = true;
            WanderingMerchantShop();
        }
        }
    });
    
    $(document).on('keyup', function(event) 
    {
        let keycode = (event.keyCode ? event.keyCode : event.which);
        if(menu=='start' && keycode == '32')
        {
            ajax_post("Add1ToNumberOfViews");
            menu = "arena";//65 50  
            Enemy1 = Enemy(1,"gold",4,8,12,Math.random()*500+20,Math.random()*500+20,100,130,200,200,25);
//            LoadEnemy1();
////            LoadEnemy1();
//            E
        }
    });
    
    
    $(document).on('keyup', function(event) 
    {
        let keycode = (event.keyCode ? event.keyCode : event.which);
        if(menu=="waitarena" && keycode == '73' && see_Invetory==false || menu=="waitarena" && keycode == '51' && see_Invetory==false)
        {
            see_Invetory = true;
        }
        else if(menu=="waitarena" && keycode == '73' && see_Invetory==true ||  menu=="waitarena" && keycode == '51' && see_Invetory==true)
        {
            see_Invetory = false;
            ItemInfo = "";
            drawInfo = false;
        }
        if(menu=="waitarena" && keycode == '83' && see_Invetory==false&&seeStatisticsTab==false)
        {
            seeStatisticsTab = true;
        }
        if(menu=="waitarena" && keycode == '27' && see_Invetory==false&&seeStatisticsTab)
        {
            seeStatisticsTab = false;
        }
    });
    
    function Player()
    {
        ctx.drawImage(PlayerIMG,x,y,szer,wys)    }
    //-----------------GRACZ---------------
    
    
    function Draw_Shop()
    {
        ctx.drawImage(SHOP_IMG,50,50,700,550);
        ctx.drawImage(SHOP_BUTTON_IMG,300,520,200,40);
        if(Items[0].ItemBuy=="no")
        {
            ctx.drawImage(SHOP_BUY_IMG,85,435,185,30);
        }
        else
        {
            ctx.drawImage(SHOP_BUYED_IMG,85,435,185,30);
        }
        if(Items[1].ItemBuy=="no")
        {
            ctx.drawImage(SHOP_BUY_IMG,305,435,185,30);
        }
        else
        {
            ctx.drawImage(SHOP_BUYED_IMG,305,435,185,30);
        }
        if(Items[2].ItemBuy=="no")
        {
            ctx.drawImage(SHOP_BUY_IMG,525,435,185,30);
        }
        else
        {
            ctx.drawImage(SHOP_BUYED_IMG,525,435,185,30);
        }
        //ITEM1
        
        if(Items[0].ItemBuy=="no")
        {
            
            DrawText(95,200,Items[0].ItemName,"red",20);
            DrawText(95,225,"Cena:"+Items[0].ItemPrice+" Rzadkość:"+Items[0].ItemRarity);
            if(Item1EffectLenght>18)
            {
                DrawText(95,250,"Efekt: "+Items[0].ItemEffect.substr(0,18),"red",15);
                DrawText(95,265,Items[0].ItemEffect.substr(18),"red",15);
            }
            else
            {
                DrawText(95,250,"Efekt: "+Items[0].ItemEffect,"red",15);
            }
            if(Items[0].ItemType=="potions")
            {
                ctx.drawImage(POTION_0_IMG, 165,270,32,32);
            }
            else if(Items[0].ItemType=="accessories")
            {
                ctx.drawImage(ACCESSORIES_IMG, 165,270,32,32);
            } 
            else if(Items[0].ItemType=="health")
            {
               ctx.drawImage(APPLE_IMG, 165,270,32,32); 
            }
            
            
        }
        //ITEM1---
        //ITEM2
        if(Items[1].ItemBuy=="no")
        {
            var text1 = Items[1].ItemEffect;
            DrawText(315,200,Items[1].ItemName,"red",20);
            DrawText(315,225,"Cena:"+Items[1].ItemPrice+" Rzadkość:"+Items[1].ItemRarity);
            if(Item2EffectLenght>18)
            {
                var text2 = Items[1].ItemEffect.substr(0,18);
                DrawText(315,250,"Efekt: "+text2,"red",15);
                DrawText(315,265,Items[1].ItemEffect.substr(18));
            }
            else
            {
                DrawText(315,250,"Efekt: "+Items[1].ItemEffect.substr(0,18),"red",15);
            }
            
            if(Items[1].ItemType=="potions")
            {
                ctx.drawImage(POTION_0_IMG, 385,270,32,32);
            }
            else if(Items[1].ItemType=="accessories")
            {
                ctx.drawImage(ACCESSORIES_IMG,385,270,32,32);
            }
            else if(Items[1].ItemType=="health")
            {
               ctx.drawImage(APPLE_IMG, 385,270,32,32); 
            }
            
        }
        //ITEM2---
        //ITEM3
        if(Items[2].ItemBuy=="no")
        {
            DrawText(540,200,Items[2].ItemName,"red",20);
            DrawText(540,225,"Cena:"+Items[2].ItemPrice+" Rzadkość:"+Items[2].ItemRarity);
            if(Item3EffectLenght>18)
            {
//                console.log(Item3EffectLenght);
                var text = Items[2].ItemEffect.substr(0,18);
                DrawText(540,250,"Efekt: "+text,"red",15);
                DrawText(540,265,Items[2].ItemEffect.substr(18));
            }
            else
            {
                DrawText(540,250,"Efekt: "+Items[2].ItemEffect,"red",15);
            }
//            DrawText(540,250,"Efekt:"+Items[2].ItemEffect,"red",15);
            if(Items[2].ItemType=="potions")
            {
                ctx.drawImage(POTION_0_IMG, 610,270,32,32);
            }
            else if(Items[2].ItemType=="accessories")
            {
                ctx.drawImage(ACCESSORIES_IMG, 610,270,32,32);
            }
            else if(Items[2].ItemType=="health")
            {
               ctx.drawImage(APPLE_IMG, 610,270,32,32); 
            }
        }
        //ITEM3
    }
    
    
    function DrawStatisticsTab()
    {
        ctx.drawImage(STATISTICS_IMG,50,50,700,550);
        DrawText(270,135,"Max Hp: ","black",20); DrawText(490,135,maxhp,"red",20);
        DrawText(270,170,"Dmg: ","red",20); DrawText(490,170,dmg,"black",20);
        DrawText(270,200,"Speed: ","black",20); DrawText(490,200,speed,"red",20);
        DrawText(250,230,"Defend Chance: ","red",20); DrawText(490,230,defendChance,"black",20);
    }
    
    function DrawWanderingMerchantShop()
    {
        ctx.drawImage(WANDERING_MERCHANT_SHOP_IMG,50,50,700,550);
        for(var x = 0; x < Number_Invetory_Basic_Slots; x++)
        {
        if(Invetory_Basic_Slots[x].SlotContent!="empty")
        {
            var nameOfItem = Invetory_Basic_Slots[x].SlotContent;
            if(nameOfItem=="LECZENIE")
            {                
                DrawItemXAndYShopMerchant(Invetory_Basic_Slots[x].SlotId,ITEM_POTION_LECZENIE_IMG,50,60);
            }
            else if(nameOfItem=="AMULET SIŁY")
            {
                DrawItemXAndYShopMerchant(Invetory_Basic_Slots[x].SlotId,ITEM_AMULET_DAMAGE_IMG,64,60);
            }
            else if(nameOfItem=="PATELNIA KUCHNI")
            {
                DrawItemXAndYShopMerchant(Invetory_Basic_Slots[x].SlotId,ITEM_PATELNIA_IMG,68,60);
            }            
        }
        }
    }
    
    function DrawItemXAndYShopMerchant(SlotId,IMG,width,height)
    {
        var x = 0;
        var y = 0;
        //X
        if(SlotId==0||SlotId==4||SlotId==8||SlotId==12||SlotId==16)
        {
            x = 395;
        }
        else if(SlotId==1||SlotId==5||SlotId==9||SlotId==13||SlotId==17)
        {
            x = 480;
        }
        else if(SlotId==2||SlotId==6||SlotId==10||SlotId==14||SlotId==18)
        {
            x = 560;
        }
        else if(SlotId==3||SlotId==7||SlotId==11||SlotId==15||SlotId==19)
        {
            x = 645;
        }
        //X
        //Y
             if(SlotId>=0&&SlotId<=3){y = 150;}
        else if(SlotId>=4&&SlotId<=7){y = 235;}
        else if(SlotId>=8&&SlotId<=11){y = 320;}
        else if(SlotId>=12&&SlotId<=15){y = 405;}
        else if(SlotId>=16&&SlotId<=19){y = 490;}
        //Y
        
        //DRAWING
        ctx.drawImage(IMG,x,y,width,height);
//        DrawText(x,y,SlotId.ItemDurability);
        //DRAWING
    }
    
    
    function Draw_Invetory()
    {
        if(InvetoryTab=="items")
        {    
        ctx.drawImage(INVETORY_IMG,50,50,700,550);
        for(var x = 0; x < Number_Invetory_Basic_Slots; x++)
        {
        if(Invetory_Basic_Slots[x].SlotContent!="empty")
        {
            var nameOfItem = Invetory_Basic_Slots[x].SlotContent;
            if(nameOfItem=="LECZENIE")
            {                
                DrawItemXAndY(Invetory_Basic_Slots[x].SlotId,ITEM_POTION_LECZENIE_IMG,50,60);
            }
            else if(nameOfItem=="AMULET SIŁY")
            {
                DrawItemXAndY(Invetory_Basic_Slots[x].SlotId,ITEM_AMULET_DAMAGE_IMG,64,60);
            }
            else if(nameOfItem=="PATELNIA KUCHNI")
            {
                DrawItemXAndY(Invetory_Basic_Slots[x].SlotId,ITEM_PATELNIA_IMG,68,60);
            }
            else if(nameOfItem=="AMULET ŻYCIA")
            {
                DrawItemXAndY(Invetory_Basic_Slots[x].SlotId,ITEM_AMULET_OF_LIFE_IMG,68,60);
            }  
        }
        }
        }
        else if(InvetoryTab=="alchemy")
        {
            ctx.drawImage(INVETORY_ALCHEMY_IMG,50,50,700,550);
            for(var x = 0; x < Number_Invetory_Potion_Slots; x++)
            {
            if(Invetory_Potion_Slots[x].SlotContent!="empty")
            {
                var nameOfItem = Invetory_Potion_Slots[x].SlotContent;
                if(nameOfItem=="ULECZ 2hp")
                {                
                    DrawItemXAndY(Invetory_Potion_Slots[x].SlotId,ITEM_POTION_LECZENIE_IMG,50,60);
                }
//              Invetory_Potion_Slots[x].SlotContent = Items[numberOfItem].ItemName;
            }
            }
            for(var x = 0; x < Number_Quick_Selection_Bar; x++)
            {
            if(QuickSelectionBar[x].SlotContent!="empty")
            {
                var nameOfItem = QuickSelectionBar[x].SlotContent;
                if(nameOfItem=="ULECZ 2hp")
                {                
                    // DrawItemXAndY(Invetory_Potion_Slots[x].SlotId,ITEM_POTION_LECZENIE_IMG,50,60);
                    if(x==0){ctx.drawImage(ITEM_POTION_LECZENIE_IMG,565,135,80,80);}
                    if(x==1){ctx.drawImage(ITEM_POTION_LECZENIE_IMG,565,245,80,80);}
                }
//              Invetory_Potion_Slots[x].SlotContent = Items[numberOfItem].ItemName;
            }
            }
        }    
    }
    
    
    var drawInfo = false;
    var ItemInfo;
    
    function DrawItemInfo()
    {
        if(drawInfo)
        {
            DrawText(x,y,":"+item,"red",18);          
        }
    }
    
    
    function ClickInvetoryInfo(X1,X2,Y1,Y2,layerX,layerY,slotId)
    {
        if(drawInfo && layerX>X1 && layerX<X2 && layerY>Y1 && layerY<Y2)
            {
                ItemInfo = "";
                drawInfo = false;
            }
        else if(drawInfo==false && layerX>X1 && layerX<X2 && layerY>Y1 && layerY<Y2)
        {
            ItemInfo = Invetory_Basic_Slots[slotId].SlotContent;
            //INFO----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            if(Invetory_Basic_Slots[slotId].SlotContent=="LECZENIE")
            {
            ItemInfo = ItemInfo + " Za każdego zabitego przeciwnika +3hp ;" + Invetory_Basic_Slots[slotId].ItemDurabilityWithSlot;
            }
            else if(Invetory_Basic_Slots[slotId].SlotContent=="AMULET SIŁY")
            {
            ItemInfo = ItemInfo + " Siła +1 ;" + Invetory_Basic_Slots[slotId].ItemDurabilityWithSlot;
            }
            else if(Invetory_Basic_Slots[slotId].SlotContent=="PATELNIA KUCHNI")
            {
            ItemInfo = ItemInfo + " Super Tarcza! ;" + Invetory_Basic_Slots[slotId].ItemDurabilityWithSlot;
            }
            else if(Invetory_Basic_Slots[slotId].SlotContent=="AMULET ŻYCIA")
            {
            ItemInfo = ItemInfo + " + 10 Max Hp ;" + Invetory_Basic_Slots[slotId].ItemDurabilityWithSlot;
            }
            //INFO---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            drawInfo = true;
        }
    }


    function ClickInvetoryInfoPotion(X1,X2,Y1,Y2,layerX,layerY,slotId)
    {
        if(drawInfo && layerX>X1 && layerX<X2 && layerY>Y1 && layerY<Y2 && Invetory_Potion_Slots[slotId].SlotContent !="empty")
            {
                ItemInfo = "";
                drawInfo = false;
            }
        else if(drawInfo==false && layerX>X1 && layerX<X2 && layerY>Y1 && layerY<Y2 && Invetory_Potion_Slots[slotId].SlotContent !="empty")
        {
            ItemInfo = Invetory_Potion_Slots[slotId].SlotContent;
            //INFO----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            if(Invetory_Potion_Slots[slotId].SlotContent=="LECZENIE")
            {
                ItemInfo = ItemInfo + " Za każdego zabitego przeciwnika +3hp";
            }
            else if(Invetory_Potion_Slots[slotId].SlotContent=="ULECZ 2hp")
            {
            ItemInfo = ItemInfo + " HP +2";
            }
            else if(Invetory_Potion_Slots[slotId].SlotContent=="PATELNIA KUCHNI")
            {
            ItemInfo = ItemInfo + " Super Tarcza!";
            }
            //INFO---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            drawInfo = true;
        }
    }
    
    can.addEventListener("click", e => {
        if(see_Invetory&&InvetoryTab=="items")
        {
            ClickInvetoryInfo(100,155,155,215,e.layerX,e.layerY,0);
            ClickInvetoryInfo(190,245,155,215,e.layerX,e.layerY,1);
            ClickInvetoryInfo(275,330,155,215,e.layerX,e.layerY,2);
            ClickInvetoryInfo(360,415,155,215,e.layerX,e.layerY,3);
            if(e.layerX>475 && e.layerX<515 && e.layerY>120 && e.layerY<160)
            {
                InvetoryTab = "alchemy";
                ItemInfo = "";
                drawInfo = false;
            }
        }
        else if(see_Invetory&&InvetoryTab=="alchemy")
        {
            ClickInvetoryInfoPotion(100,155,155,215,e.layerX,e.layerY,0);
            ClickInvetoryInfoPotion(190,245,155,215,e.layerX,e.layerY,1);
            ClickInvetoryInfoPotion(275,330,155,215,e.layerX,e.layerY,2);
            ClickInvetoryInfoPotion(360,415,155,215,e.layerX,e.layerY,3);
            if(IsClick(100,155,155,215,e.layerX,e.layerY,0)&&Invetory_Potion_Slots[0].SlotContent!="empty")
                {
                    MovingPotion = true;
                    ItemMemory = Invetory_Potion_Slots[0].SlotContent;
                    IdFromPotions = 0;
                }
            
            
    
        }
        
    });
    
    var MovingPotion = false;
    var ItemMemory = "empty";
    var IdFromPotions;
    var IdFromQuikPotions;
    
//    var QuickItemMemory;
//    function MovingAnItemInInvetory(slotId,etap)
//    {
//        console.log(ItemMemory);
//        if(etap==0)
//        {
//            ItemMemory = Invetory_Potion_Slots[slotId].SlotContent;
//        }
//        if(etap==1)
//        {
//            QuickSelectionBar[slotId].SlotContent = ItemMemory;
//            // Invetory_Potion_Slots[slotId].SlotContent = "empty";
//        }
//        if(etap==2)
//        {
//            Invetory_Potion_Slots[slotId].SlotContent = QuickItemMemory;
//        }    
//    }
//    
    function IsClick(X1,X2,Y1,Y2,layerX,layerY,slotId)
    {
        if(layerX>X1 && layerX<X2 && layerY>Y1 && layerY<Y2)
        {
            return true;
        }
    }
    var wait = false;
//    var movingElementInvetory = false;
    
//    var fromInvetory = "null";

    can.addEventListener("dblclick", e => 
        {
        
        if(see_Invetory&&InvetoryTab=="alchemy")
        {   
            console.log("DOUBLE CLICK");
//            if(e.layerX>475 && e.layerX<515 && e.layerY>120 && e.layerY<160&&InvetoryTab=="alchemy")
//            {
//                InvetoryTab = "items";
//                ItemInfo = "";
//                drawInfo = false;
//            }
            
            if(IsClick(565,645,135,210,e.layerX,e.layerY,0)&&MovingPotion==false)
            {
                console.log("DOUBLE CLICK111");
                MovingPotion = true;
                ItemMemory = QuickSelectionBar[0].SlotContent;
                IdFromQuikPotions = 0;
//                for(var x = 0; x > Number_Quick_Selection_Bar; x++)
//                {
//                    console.log("ABC");
//                    if(QuickSelectionBar[x].SlotContent=="empty")
//                    {
//                        x = x + Number_Quick_Selection_Bar;
//                        Invetory_Potion_Slots[0].SlotContent = "empty";
//                        QuickSelectionBar[x].SlotContent = Invetory_Potion_Slots[0].SlotContent;
//                    }
//                }
                
            }
//       
        }
    });

    var IdOfFromQuickSlot;
    function Milisekunds20()
    {
        if(wait==true)
        {
            wait = false;
        }
    }
    
    setInterval(Milisekunds20,20);

    function DrawItemXAndY(SlotId,IMG,width,height)
    {
        var x = 0;
        var y = 0;
        //X
        if(SlotId==0||SlotId==4||SlotId==8||SlotId==12||SlotId==16)
        {
            x = 100;
        }
        else if(SlotId==1||SlotId==5||SlotId==9||SlotId==13||SlotId==17)
        {
            x = 185;
        }
        else if(SlotId==2||SlotId==6||SlotId==10||SlotId==14||SlotId==18)
        {
            x = 270;
        }
        else if(SlotId==3||SlotId==7||SlotId==11||SlotId==15||SlotId==19)
        {
            x = 355;
        }
        //X
        //Y
             if(SlotId>=0&&SlotId<=3){y = 160;}
        else if(SlotId>=4&&SlotId<=7){y = 240;}
        else if(SlotId>=8&&SlotId<=11){y = 330;}
        else if(SlotId>=12&&SlotId<=15){y = 415;}
        else if(SlotId>=16&&SlotId<=19){y = 495;}
        //Y
        
        //DRAWING
        ctx.drawImage(IMG,x,y,width,height);
//        DrawText(x,y,SlotId.ItemDurability);
        //DRAWING
    }
    
    
    function Buy_Item_Shop(numberOfItem)
    {
        if(Items[numberOfItem].ItemBuy=="no")
        { 
        if(playerGold>=Items[numberOfItem].ItemPrice)
        {
            playerGold = playerGold - Items[numberOfItem].ItemPrice;
            Items[numberOfItem].ItemBuy = "yes";
//            console.log("KUPIONO"+Items[numberOfItem].ItemBuy);
        }
        else
        {
            console.warn("Nie stać cię.");
            console.error("Nie stać cię.");
        }
        }
    }
    
    
    function Effect_Bought_Item(numberOfItem)
    {
        if(Items[numberOfItem].ItemBuy=="yes")
        {    
        if(Items[numberOfItem].ItemEffectNow)
        {
            if(Items[numberOfItem].ItemEffect=="hp+8")
            {
                hp = hp + 8;
                if(hp>maxhp)
                {
                    hp = maxhp;
                }
            }
            else if(Items[numberOfItem].ItemEffect=="speed+1")
            {
                speed = speed + 1;
            }
        }
        }
    }
    
    
    can.addEventListener("click", e => {
        
        if(e.layerX>=300 && e.layerX<=500 && e.layerY>=520 && e.layerY<=560&&menu=="shop")
            {
                menu="waitarena";
                SX = 0;
                SY = 0;
            };
    });
    
    
    can.addEventListener("click", e => {
        
        if(e.layerX>=85 && e.layerX<=270 && e.layerY>=435 && e.layerY<=465&&menu=="shop"&&Items[0].ItemBuy=="no")
            {
//                console.log(Items[0].ItemEffect);
                Buy_Item_Shop(0);
                Effect_Bought_Item(0);
                AddItemToInvetory(0);
            }
        if(e.layerX>=305 && e.layerX<=490 && e.layerY>=435 && e.layerY<=465&&menu=="shop"&&Items[1].ItemBuy=="no")
            {
//                console.log(Items[1].ItemEffect);
                Buy_Item_Shop(1);
                Effect_Bought_Item(1);
                AddItemToInvetory(1);
            }
        if(e.layerX>=525 && e.layerX<=710 && e.layerY>=435 && e.layerY<=465&&menu=="shop"&&Items[2].ItemBuy=="no")
            {
//                console.log(Items[2].ItemEffect);
                Buy_Item_Shop(2);
                Effect_Bought_Item(2);
                AddItemToInvetory(2);
            }
    });
    
    var ShopX = 360,ShopY = 90, ShopSzer = 120, ShopWys = 60;
    var ToBossX = 120,ToBossY = 540, ToBossSzer = 120, ToBossWys = 120;
    var ToWanderingMerchantX = 715,ToWanderingMerchantY = 225, ToWanderingMerchantSzer = 95, ToWanderingMerchantWys = 180;
    var ShopVisited = false;
    function DRAW_ALL()
    {
        CLEAR();
//        ctx.drawImage(i1,0,0);
        if( menu=="waitarena")
        {
            ctx.fillStyle = "#660000";
            ctx.fillRect(ShopX,ShopY,ShopSzer,ShopWys);
            DrawText(360,120,"SKLEP","black",18);
            
            if(PossibleBossFight)
            {
                ctx.fillStyle = "#ab112b";
                ctx.fillRect(ToBossX,ToBossY,ToBossSzer,ToBossWys);
                DrawText(150,580,"BOSS","black",18);
            }
            if(WanderingMerchantPasses)
            {
                ctx.fillStyle = "#3dcf29";
                ctx.fillRect(ToWanderingMerchantX,ToWanderingMerchantY,ToWanderingMerchantSzer,ToWanderingMerchantWys);
                DrawText(720,250,"KUPIEC","black",17);
            }
            
        }
        if(menu=="defeat")
        {
            PlayerIMG = CHARACTER_DEAD_IMG;
            Player();
            DrawText(310,250,"UMARŁEŚ","#b32400",50);
        }
		
        if(menu=="arena"||menu=="waitarena")
        { 
//            ctx.drawImage(20,300,IMG_HEARTH);
//            DrawText(20,35,"Życie:"+hp+"/"+maxhp+"      "+"Tarcza:"+defendHP,"black",30);
            DrawText(50,35,hp+"/"+maxhp+"      "+"Tarcza:"+defendHP,"black",30);
            Player();
        }
        if(defendBREAK==0){DrawText(170,70,"Tarcza złamana","black",30);}
        
        if(see_Invetory && menu=="waitarena")
        {
            Draw_Invetory();
            
        }
        
        
        if(drawInfo)
        {
            DrawText(85,115," "+ItemInfo,"red",18);
        }
        
        if(menu=="shop")
        {
            DrawText(50,35,hp+"/"+maxhp+"      "+"Tarcza:"+defendHP,"black",30);
            Draw_Shop();
        }
        if(seeStatisticsTab)
        {
            DrawStatisticsTab();
        }
        if(WanderingMerchantShopSee)
        {
            DrawWanderingMerchantShop();
        }
        
        if(EISLIVE!=undefined)
        {
        if(EISLIVE&&menu!="defeat"&&menu!="start")
        {
           Draw_Enemy(rightIndexAnimation,leftIndexAnimation,SlashIndexAnimation); 
//           console.log(EX+" "+EY);   
           DrawText(EX+ESZER/2-8,EY,EHP,"red",18);
        }
        }
        //Draw_obstacle();
        DrawText(400,35,playerGold,"#04250b",24);
        ctx.drawImage(IMG_EMERALD,373,10,30,30);
        ctx.drawImage(IMG_KILLS,540,10,28,40);
        ctx.drawImage(IMG_HEARTH,3,3,45,45);
        DrawText(575,35,kills,"red",22);
        IsDead();
        EnemyISDead();
    }
    
    
//    var i1 = new Image(20,800);
//    i1.src = 'item/a1.png';
    
    setInterval(EnemyMoveToPlayerX,35);
    setInterval(EnemyMoveToPlayerY,35);
    
    var lenght1 = ItemList.length;
    var Item1EffectLenght;
    var Item2EffectLenght;
    var Item3EffectLenght;
    
    function Shop()
    {
        menu = "shop";
        Random3Items();
        ItemsGetTextEffectLenght();
    }
    
    function WanderingMerchantShop()
    {
        WanderingMerchantHowMuchItems = lenght1;
        RandomItemsWanderingMerchantShop();
    }
    
    function ItemsGetTextEffectLenght()
    {
        Item1EffectLenght = Items[0].ItemEffect.length;
        Item2EffectLenght = Items[1].ItemEffect.length;
        Item3EffectLenght = Items[2].ItemEffect.length;
    }
    
    function RandomItemsWanderingMerchantShop()
    {
        var random2 = Math.floor(Math.random()*20+8);
        console.log(random2);
        for(var i = 0; i < WanderingMerchantHowMuchItems; i++)
        {
            var random1 = Math.floor(Math.random()*lenght1+0);
            WanderingMerchantItems[i] = ItemList[random1];
            console.log("Random"+i+" "+random1+" "+"Item "+ItemList[random1].ItemName);
        }
        console.table(WanderingMerchantItems);
        
        
//        for(var i = 0; i < WanderingMerchantHowMuchItems; i++)
//        {
//            console.log("A");
//        }
//        var random2 = Math.floor(Math.random()*lenght1+0);
//        var random3 = Math.floor(Math.random()*lenght1+0);
//        Items[0] = "";
//        Items[1] = "";
//        Items[2] = "";
//        var r11 = random1;
//        var r22 = random2;
//        var r33 = random3;
//        var r1;
//        var r2;
//        var r3;
//        
//        if(random1==random2||random1==random3)
//        {
//            r1 = true;
//        }
//        else
//        { 
//            Items[0] = ItemList[random1];
//        }
//        while(r1)
//        {
//            random1 = Math.floor(Math.random()*4+0);
//            if(random1==random2||random1==random3)
//            {
//            r1 = true;
//            }
//            else
//            {
//                r1 = false;
//                Items[0] = ItemList[random1];
//            }
//        }
//
//	if(random2==random1||random2==random3)
//        {
//            r2 = true;
//        }
//        else
//        { 
//            Items[1] = ItemList[random2];
//        }
//        while(r2)
//        {
//            random2 = Math.floor(Math.random()*4+0);
//            if(random2==random1||random2==random3)
//        	{
//            r2 = true;
//        	}
//            else
//            {
//                r2 = false;
//                Items[1] = ItemList[random2];
//            }
//        }
//	if(random3==random1||random3==random2)
//        {
//            r3 = true;
//        }
//        else
//        { 
//            Items[2] = ItemList[random3];
//        }
//        while(r3)
//        {
//            random3 = Math.floor(Math.random()*4+0);
//            if(random3==random1||random3==random2)
//        	{
//            r3 = true;
//        	}
//            else
//            {
//                r3 = false;
//                Items[2] = ItemList[random3];
//            }
//        }
////	console.log(random1,random2,random3);
//    Items[0].ItemBuy = "no";
//    Items[1].ItemBuy = "no";
//    Items[2].ItemBuy = "no";
////    console.log(Items);    

    }
    
    
    
    function Random3Items()
    {
        var random1 = Math.floor(Math.random()*lenght1+0);
        var random2 = Math.floor(Math.random()*lenght1+0);
        var random3 = Math.floor(Math.random()*lenght1+0);
        Items[0] = "";
        Items[1] = "";
        Items[2] = "";
        var r11 = random1;
        var r22 = random2;
        var r33 = random3;
        var r1;
        var r2;
        var r3;
        
        if(random1==random2||random1==random3)
        {
            r1 = true;
        }
        else
        { 
            Items[0] = ItemList[random1];
        }
        while(r1)
        {
            random1 = Math.floor(Math.random()*4+0);
            if(random1==random2||random1==random3)
            {
            r1 = true;
            }
            else
            {
                r1 = false;
                Items[0] = ItemList[random1];
            }
        }

	if(random2==random1||random2==random3)
        {
            r2 = true;
        }
        else
        { 
            Items[1] = ItemList[random2];
        }
        while(r2)
        {
            random2 = Math.floor(Math.random()*4+0);
            if(random2==random1||random2==random3)
        	{
            r2 = true;
        	}
            else
            {
                r2 = false;
                Items[1] = ItemList[random2];
            }
        }
	if(random3==random1||random3==random2)
        {
            r3 = true;
        }
        else
        { 
            Items[2] = ItemList[random3];
        }
        while(r3)
        {
            random3 = Math.floor(Math.random()*4+0);
            if(random3==random1||random3==random2)
        	{
            r3 = true;
        	}
            else
            {
                r3 = false;
                Items[2] = ItemList[random3];
            }
        }
//	console.log(random1,random2,random3);
    Items[0].ItemBuy = "no";
    Items[1].ItemBuy = "no";
    Items[2].ItemBuy = "no";
//    console.log(Items);    

    }
    
    function Item(name,price,type,rarity,effect,effect_now,is_item_to_invetory,
    buy="no",durability="null")
    {
        this.ItemName = name;
        this.ItemPrice = price;
        this.ItemType = type;
        this.ItemRarity = rarity;//0 - zwykły //1 - niezwykły //2 - rzadki //3 - epicki //4 -  legendarny
        this.ItemEffect = effect;
        this.ItemBuy = buy;
        this.ItemEffectNow = effect_now;
        this.ItemIsItemToInvetory = is_item_to_invetory;
        this.ItemDurability = durability;
    }
    
    IsDead();
    function IsDead()
    {
        if(hp<=0)
            {
                menu="defeat";
            }
    }
    
    function EnemyISDead()
    {
        if(EHP<=0&&menu!="shop"&&EISLIVE==true)
        {
            if(SaveHp)
            {
                var TestHp = hp + SabeHpNumber;
                if(TestHp>maxhp)
                {
                    hp = maxhp;
                }
                else
                {
                    hp = hp + SabeHpNumber;
                }
                for(var x = 0; x < Number_Invetory_Basic_Slots; x++)
                {
                    if(Invetory_Basic_Slots[x].SlotContent=="LECZENIE")
                    {
                        Invetory_Basic_Slots[x].ItemDurabilityWithSlot = Invetory_Basic_Slots[x].ItemDurabilityWithSlot - 1;
                        if(Invetory_Basic_Slots[x].ItemDurabilityWithSlot==0)
                        {
                            Invetory_Basic_Slots[x].SlotContent = "empty";
                            Invetory_Basic_Slots[x].ItemDurabilityWithSlot = -1;
                            SabeHpNumber = SabeHpNumber - 3;
                        }
                    }
                }
            }
            EISLIVE = false;
            kills = kills +1;
            toShop++;
            toWaitArena++;
            WanderingMerchantMeter++;
            EnemyDrop(EDROP,EITEMSNUMBERDROP,'null',0);
            ajax_post("Add1ToNumberOfDefeatedEnemies");
            if(toWaitArena==1)
            {
                toWaitArena = 0;
                menu = "waitarena";
            } 
            else
            {
                EISLIVE = true;
            } 
            
            
            if(WanderingMerchantMeter==WanderingMerchantWhenPasses)
            { 
                WanderingMerchantPasses = true;      
            }
            
        }
    }
    
    
    function EnemyDrop(Drop,Size,NameOfItem='null',numberOfItem=-1)
    {
        if(Drop=="gold")
        {
            var sizeA = parseInt(Size);
            playerGold = playerGold + sizeA;
        }
        else if(Drop=="hp")
        {
            hp = hp + Size;
        }
        else if(Drop=="item")
        {
            ItemGift(NameOfItem,numberOfItem);
        }
    }
    
    function ItemGift(nameOfItem,numberOfItem=-1)
    {
        if(numberOfItem!=-1)
        {
            var IsAdd = false;
            for(var x = 0; x < Number_Invetory_Basic_Slots; x++)
            {
                if(Invetory_Basic_Slots[x].SlotContent=="empty")
                {
                    Invetory_Basic_Slots[x].SlotContent = ItemList[numberOfItem].ItemName;
                    Invetory_Basic_Slots[x].ItemDurabilityWithSlot = ItemList[numberOfItem].ItemDurability;
                    x = Number_Invetory_Basic_Slots + 20;
                    console.log(Invetory_Basic_Slots);
                    IsAdd = true;
                    EffectItemInInvetory();
                    console.table(Invetory_Basic_Slots);
                }
            }
            if(IsAdd==false)
            {
                console.warn("NIE MA MIEJSCA!");
            }
        }
        
    }
    
    var ox = 120,oy=60, oszer = 120, owys = 30;
    function Draw_obstacle()
    {
        ctx.fillStyle = "black";    
        ctx.fillRect(ox,oy,oszer,owys);  
    }
    
    function Draw_Enemy(i,i2,i3)
    {
        if(ERIGHT=="here"||EDOWN=="here"&&ERIGHT=="none"&&ELEFT=="none")
        {
            ctx.drawImage(ENEMY_RIGHT_ARRAY[i],EX,EY,ESZER,EWYS);
        }
        if(ELEFT=="here"||EUP=="here"&&ELEFT=="none"&&ERIGHT=="none")
        {
            ctx.drawImage(ENEMY_LEFT_ARRAY[i2],EX,EY,ESZER,EWYS);
        }
        if(SlashAnimation==true&&SlashIndexAnimation<7)
        {
//           ctx.drawImage(ENEMY_SLASH_ARRAY[i3],EX,EY,ESZER,EWYS); 
        }
    }
    
    function FrameEnemy()
    {
        if(ERIGHT=="here")
        {
            rightIndexAnimation++; 
            if(rightIndexAnimation >=9)
            {
                rightIndexAnimation = 0;
            }
        }
        if(ELEFT=="here")
        { 
            leftIndexAnimation++; 
            if(leftIndexAnimation >= 9)
            {
                leftIndexAnimation = 0;
            }
        }
    }
    setInterval(FrameEnemy,150);
    
    var SlashIndexAnimation = 0;
    function FrameSlashEnemy()
    {
        if(SlashAnimation)
        {    
            SlashIndexAnimation++; 
            if(SlashIndexAnimation >= 6)
            {
                SlashAnimation = 0;
            }
        }
        else
        {
            SlashAnimation = 0;
        }
    }
    setInterval(FrameSlashEnemy,100);
    
    function CLEAR()
    {
        ctx.clearRect(0,0,can.width,can.height);
    }
    
    
    var rightIndexAnimation = 0;
    var leftIndexAnimation = 0;
    
    function EnemyWherePlayerX()
    {
        //250>80
        //250>80
        if(x<EX)
        {
             ELEFT = "here";
             ERIGHT = "none";
             EDX = 4;
             EobjectiveX = x;
             //console.log("Left");
            EnemyIMG = ENEMY_CHARACTER_LEFT_IMG;
        }
        //80>250
        if(EX<x)
        {
            ELEFT = "none";
            ERIGHT = "here";
            EDX = 4;
            EobjectiveX = x;
            //console.log("Right!");
        }
            
    }
    
    function EnemyWherePlayerY()
    {
        if(y>EY)
        {
            
            
            EUP = "none";
            EDOWN = "here";
            EDY =  5;
            //console.log("down");
            
            EobjectiveY = y;
        }
        
        
        if(y<EY)
        {
            
            EUP = "here";
            EDOWN = "none";
            EDY =  5;
            
            //console.log("up");
            EobjectiveY = y;
            
        }
        
    }
    
    async function EnemyMoveToPlayerY()
    {
        
        if(ESTATE=="quest"&&menu!="start")
        {  
        if(EDOWN=="here")
        {
            EY = EY + EDY;
        }
        
        if(EUP=="here")
        {
            EY = EY - EDY; 
        }
        
        if(EobjectiveY==EY||EobjectiveY-1==EY||EobjectiveY-2==EY||EobjectiveY-3==EY||EobjectiveY-4==EY||EobjectiveY+1==EY||EobjectiveY+2==EY||EobjectiveY+3==EY||EobjectiveY+4==EY)
        {
            EDY = 0;
            EDOWN = "none";
            EUP = "none";
            //console.log(EY);
        }
        }
    }
    
    async function EnemyMoveToPlayerX()
    {
        if(ESTATE=="quest"&&menu!="start")
        {  
        if(ERIGHT=="here")
        {
            EX = EX + EDX;
        }
        
        if(ELEFT=="here")
        {
            EX = EX - EDX;
        }
        
        if(EobjectiveX==EX||EobjectiveX-1==EX||EobjectiveX-2==EX||EobjectiveX-3==EX||EobjectiveX-4==EX||EobjectiveX+1==EX||EobjectiveX+2==EX||EobjectiveX+3==EX||EobjectiveX+4==EX)
        {
            EDX = 0;
            ELEFT = "none";
            ERIGHT = "none";
            //console.log(EX);
        }
        }
    }
    
    function DrawText(x, y, text,color,wielkosc)
    {
        ctx.fillStyle = color;
        ctx.font = wielkosc+"px"+" Sans-serif";
        ctx.fillText(text, x, y);
    }


    function ColisionWithPlayer()
    {
        if(x + szer < EX||
             EX + ESZER < x||
             y  + wys < EY||
            EY + EWYS < y)
            {
                ESTATE = "quest";
                ESTATE2 = "none";
                kolizja = false;
            }
        else
        {
            kolizja = true;
            if(ESTATE2!="attack" && ESTATE2!="wait")
            {
                ESTATE2 = "readyattack";
                //console.log(ESTATE);
            }
                
        }
    }
    var kolizja = false;
    
    function Enemy(defendChance,drop,numberofitemsdrop,mindmg,maxdmg,x,y,szer,wys,spawnX,spawnY,hp,dx,dy,objectiveX,objectiveY,state="quest",state2="none",left="none",right="none",down="none",up="none",islive=true)
    {
        this.EDEFENDCHANCE = defendChance;
        this.EDROP = drop;
        this.EITEMSNUMBERDROP = numberofitemsdrop;
        this.EX = x;
        this.EY = y;
        this.ESZER = szer;
        this.EWYS = wys;
        this.ESPAWNX = spawnX;
        this.EPSAWNY = spawnY;
        this.EHP = hp;
        this.ESTATE = state;
        this.ESTATE2 = state2;
        this.ELEFT = left;
        this.ERIGHT = right;
        this.EUP = up;
        this.EDOWN = down;
        this.EDX = dx;
        this.EDY = dy;
        this.EobjectiveX = objectiveX;
        this.EobjectiveY = objectiveY;
        this.EMINDMG = mindmg;
        this.EMAXDMG = maxdmg;
        this.EISLIVE = islive;
    }

    
    var cel = false;
    var a = true;
    var SX = x;
    var SY = y;
    var lewo;
    var prawo;
    var gora;
    var dol;
    var czy_kolizja = false;
    var PX;
    can.addEventListener("click", e => 
    {
        PX = e.offsetX;
        PY = e.offsetY;
        console.log(e.layerX + " " + e.layerY);
        
                
        
        
        //X-------------------------------------------------------
        if(menu!="shop"&&see_Invetory==false)
        {    
        if(x>e.layerX)
        {
            
            cel = true;
            prawo = false;
            lewo = true;
            dx = speed;
            SX = e.layerX;
            //console.log("BUM2!!");
            direction1 = "left";
            PlayerIMG = CHARACTER_LEFT_IMG;
        }
        else if(x<e.layerX)
        {
            
            cel = true;
            lewo = false;
            prawo = true;
            dx = speed;
            //console.log(dx);
            SX = e.layerX;
            //console.log("BUM!!");
            direction1 = "right";
            PlayerIMG = CHARACTER_RIGHT_IMG;
            
        }
        //X-------------------------------------------------------
        
        //Y-------------------------------------------------------
        if(y>e.layerY)
        {
            
            cel = true;
            gora = true;
            dol = false;
            dy =  speed;
            SY = e.layerY;
            //console.log("BUMy!!");
            direction2 = "up";
            PlayerIMG = CHARACTER_UP_IMG;
        }
        else if(y<e.layerY)
        {
            
            cel = true;
            gora = false;
            dol = true;
            dy =  speed;
            SY = e.layerY;
            //console.log("BUMy2222!!");
            direction2 = "down";
           PlayerIMG = CHARACTER_STAY_IMG;
        }
        }
        //Y-------------------------------------------------------
        
    });

    function MoveX()
    {
        //250>=360
        if(menu=="arena"||menu=="waitarena" && see_Invetory==false)
        {    
        if(x>=SX&&prawo||x>=750&&prawo)
        {
            dx = 0; 
//            PlayerIMG = CHARACTER_STAY_IMG;
        }
        if(czy_kolizja&&prawo)
            {
                dx = 0;
                x - 2;
                czy_kolizja = false;
            }
        if(prawo)
        {
            x = x + dx;
            PlayerIMG = CHARACTER_RIGHT_IMG;
        }
        
        //
        if(x<=SX&&lewo||x<=00&&lewo)
            {
//                PlayerIMG = CHARACTER_STAY_IMG;
                dx = 0;
                
            }
        if(czy_kolizja&&lewo)
            {
                dx = 0;
                x + 2;
                czy_kolizja = false;
            }
        if(lewo)
        {
            //console.log("L"+ dx);
//            PlayerIMG = CHARACTER_LEFT_IMG;
            x = x  - dx;
            
        }
        
        }
    }
    
    function MoveY()
    {
        if(menu=="arena"||menu=="waitarena" && see_Invetory==false)
        { 
        if(y<=SY&&gora)
        {
            dy = 0;
            //console.log("MAMATRATaetg");
        }
        if(gora)
        {
            y = y -dy;
//            PlayerIMG = CHARACTER_UP_IMG;
        }
        if(y>=SY&&dol||y>=580&&dol)
            {
                dy = 0;
                //console.log("MAMATRATaetg");
            }
        if(dol)
            {
                y = y + dy;
//                PlayerIMG = CHARACTER_DOWN_IMG;
            }
        }
    }
    
    
    function FPS()
    {
        
           
        CLEAR();
        
        DRAW_ALL();    
    }
        
    
    function TimerAttack()
    {
        if(mytime!=0)//1000!=0 // 2: 
        {
            mytime = mytime - 100;//1000-100=900;
            //console.log(mytime);//900
        }
        if(mytime==0)
        {  
            ESTATE2 = "attack";
            //console.log(ESTATE);
            
        }
    }
    
    var mytime;
    
    function Attacking()
    {
        if(ESTATE2=="readyattack")
        {
            mytime = 800;
            ESTATE2 = "wait";
//            SlashAnimation = false;
        }
        
    }
    
    function ENEMY_AI()
    {
        
        if(EISLIVE&&menu=="arena")
        {
//            console.log("AI Działa");
        ColisionWithPlayer();
        Attacking();
        EnemyWherePlayerX();
        EnemyWherePlayerY();
        if(ESTATE2=="attack")
        {
            SlashAnimation = true;
            var saved;
            if(defendChance!=0)
            {
                for(var i = 0; i < defendChance; i++) 
                {
                var chance = Math.floor(Math.random()*5+1);
                var chance2 = Math.floor(Math.random()*5+1);
                if(chance==chance2)
                {
                    ESTATE2 = "wait";
                    saved = true;
                    console.log("DEFEND CHANCE");
                }
                else
                {
                    saved = false;
                }
                }
            }
            else
            {
                saved = false;
            }
            
            var DMG = Math.floor(Math.random()*EMAXDMG+EMINDMG);
            if(defendBREAK!=0 && saved==false)
            {
                var  TestDMG = DMG - defendHP;// 10 - 29 = -19
                if(SuperDefend)
                    {
                        for(var x = 0; x < Number_Invetory_Basic_Slots; x++) { 
                        if(Invetory_Basic_Slots[x].SlotContent=="PATELNIA KUCHNI" ) 
                        {
                            Invetory_Basic_Slots[x].ItemDurabilityWithSlot=Invetory_Basic_Slots[x].ItemDurabilityWithSlot - 1; } 
                            if(Invetory_Basic_Slots[x].ItemDurabilityWithSlot==0)
                            {
                                Invetory_Basic_Slots[x].SlotContent = "empty";
                                Invetory_Basic_Slots[x].ItemDurabilityWithSlot = -1;
                                defendHP = oldDefendHp;
                                SuperDefend = false;
                            }
                        }
                    }
                defendBREAK = defendBREAK - 1;
                if(TestDMG<0)
                {

                }
                else
                {
                    hp = hp - DMG;//60 -(-19) = 60 + 19  NIE MOŻE TAK BYĆ!!!
                }
                
                if(kolizja==true)
                {
                    ESTATE2 = "readyattack";  
                }
                 
            }
            else if(saved==false)
            {
                playerGold - 1;
                hp = hp - DMG;
                if(kolizja==true)
                {
                   ESTATE2 = "readyattack";
                }
                
            }
            
        }
        }
    }
    function PATELNIAKUCHNI_Tarcza()
    {
        if(SuperDefend&&defendHP<15)
        {
              defendHP = defendHP + 12;
//            console.log(defendHP);
        }
        else if(SuperDefend==false)
        {
            defendHP = oldDefendHp;
        }
       
    }
    var oldDefendHp = defendHP;
    
    function DefendBreakReload()
    {
        defendBREAK = 1;
    }
function CheckThePositionOfEnemy()
    {
        //250>800 250<0
        if(EX>800||EX<0)
        {
            EX = 250;
            console.warn("EX!!!");
        }
        if(EY>600||EY<0)
        {
            EY = 250;
            console.warn("EY!!!");
        }
//        console.log("CHECKING");
    }
    //--------------------------------------------FUNKCJE---------------------------------
    setInterval(MoveX,35);
    setInterval(MoveY,35);
    setInterval(DefendBreakReload,5000);
    setInterval(FPS, 10);
    setInterval(ENEMY_AI, 25);
    setInterval(TimerAttack,100);
    setInterval(PATELNIAKUCHNI_Tarcza,3000);
    setInterval(CheckThePositionOfEnemy,250);
    
    </script>

</html>
