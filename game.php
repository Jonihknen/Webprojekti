
<script type="text/javascript">

    var config = {
        type: Phaser.AUTO,
	    parent: 'peli',
        width: 1000,
        height: 600,
        physics: {
        default: 'arcade',
        arcade: {
            debug: false
                }
        },
        scene: {
            preload: preload,
            create: create,
            update: update
        }
    };

    var game = new Phaser.Game(config);
//------START OF PRELOAD-------------------------------------------------------------------------------------------------------------------
    function preload ()
    {
        //-------------LOADING THE ASSETS-----------------------------------------
        this.load.image('wall', './assets/wall1.png'); //Background
        this.load.image('gamewin', './assets/gamewin.png');
        this.load.image('backwall', './assets/wall2.png');
        this.load.image('ground', './assets/ground.png');
        this.load.image('upbar', './assets/bar.png');
        this.load.image('enemy', './assets/enemy.png');
        this.load.image('enemy2', './assets/enemy2.png');
        this.load.image('enemy3', './assets/enemy3.png');
        this.load.image('boss', './assets/boss.png');
        this.load.image('life', './assets/life.png');
        this.load.image('shield', './assets/shield.png');
        this.load.image('finalboss', './assets/finalboss.png');
        this.load.image('bullet', './assets/bullet.png');
        this.load.image('me', './assets/me.png');
        this.load.audio('megaman', ['assets/audio/megaman.mp3']);
        this.load.audio('love', ['assets/audio/love.mp3']);
        this.load.audio('seagulls', ['assets/audio/seagulls.mp3']);
        this.load.audio('hit', ['assets/audio/hit.mp3']);
        this.load.audio('shoot', ['assets/audio/shoot.mp3']);
        this.load.audio('enemyhit', ['assets/audio/enemyhit.mp3']);
        this.load.audio('boss', ['assets/audio/boss.mp3']);
        //this.load.spritesheet('me', './assets/me.png', { frameWidth: 22, frameHeight: 33 },);
    }
    //------------START OF CREATE-------------------------------------------------------------------------------------------------------------
    function create () 
    {
        //-------------VARIABLES------------------------------------------------------------------------------------------------------------
        this.score = 0;
        this.scoreText;
        this.hitpoints; //hp text
        this.hp = 1000000;
        this.nohits = 0; //invincibility timer
        this.timer = 0; //timer for enemy waves and stuff
        this.bosshp = 0;
        this.norungun = 0; //pause between shots
        this.shieldtimes = 0; //Shieldtimer
        this.wavetimer = 1; //Enemywavetimer
        this.difficulty = 1; //startingdifficulty
        this.setdifficulty = 2; //It gets harder as this rises, start at 2
        this.bossisdead = 0; //checks if the boss is wasted
        this.gamecompleted = 1; //Gameover after last boss
        this.trackselect = 0; //Changes songs after bosses
        this.play = 0; //play only once
        //soundvariables
        this.shootsound = this.sound.add('shoot');
        this.gettinghitsound = this.sound.add('hit');
        this.enemyhitsound = this.sound.add('enemyhit');
        this.bossexplode = this.sound.add('boss');

        //this.input.mouse.disableContextMenu();
        //-----------BACKGROUND IMAGE--------------------------------------------------------------------------------------------------------------
        this.backgroundimage = this.add.image(600, 350, 'wall');
        //-------------WALLS AND UPBAR------------------------------------------------------------------------------------------------------------
        platforms = this.physics.add.staticGroup();
        platforms.create(100, 610, 'ground');
        platforms.create(735, 610, 'ground');
        platforms.create(1000, 610, 'ground');
        platforms.create(250, 28, 'upbar');
        platforms.create(850, 28, 'upbar');
        platforms.create(1250, 28, 'upbar');
        platforms.create(1100, 350, 'backwall').setScale(2).refreshBody();
        //platforms.create(-50, 350, 'backwall').setScale(2).refreshBody();
        //--------------MAKING THE PLAYER-----------------------------------------------------------------------------------------------------------
        this.player = this.physics.add.sprite(100, 400, 'me').setBounce(0.8).setCollideWorldBounds(true);
        this.physics.add.collider(this.player, platforms);
        //----------ARROWKEYS----------------------------------------------------------------------------------------------------------------
        //cursors = this.input.keyboard.createCursorKeys();
        //------------UPTEXTS--------------------------------------------------------------------------------------------------------------
        this.scoreText = this.add.text(16, 6, 'Points: 0', { fontSize: '32px', fill: '#ff0' });
        this.scoreText.setStroke('#0381d1', 16).setShadow(2, 2, "#333333", 2, true, true);
        this.hitpoints = this.add.text(300, 6, 'HP: 3', { fontSize: '42px', fill: '#ff0000' });
        this.hitpoints.setStroke('#0381d1', 16).setShadow(2, 2, "#333333", 2, true, true);
        this.bosshitpoints = this.add.text(650, 6, 'BossHP: 0', { fontSize: '32px', fill: '#ff0' });
        this.bosshitpoints.setStroke('#0381d1', 16).setShadow(2, 2, "#333333", 2, true, true);
        this.speakingtext = this.add.text(200, 200, 'PHASESHOOTER', { fontSize: '42px', fill: '#ff0' });
        //-----------PLAYER GETTING HIT BY NORMAL ENEMY---------------------------------------------------------------------------------------------------------------
        function hit (player, enemy)
        {
            if (this.nohits < 1){
                this.hp -= 1;
                this.nohits = 60;
                this.hitpoints.setText('HP: ' + this.hp);
                enemy.setActive(false);
                enemy.setVisible(false);
                enemy.disableBody(true, true);
                this.gettinghitsound.play();
            }
            if(this.hp < 0) {
                this.player.setTint(0xff0000);
                this.physics.pause();
                this.bossexplode.play();
                var points = this.score;
                this.speakingtext.setText('DEATH! \nYOU SCORED '+points+' POINTS!');
                //MUUTTUJIEN LÄHETYS
                var delayInMilliseconds = 5000; //5 second
                setTimeout(function() {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function(){
                        if(this.readyState==4){
                            window.location.href = "highscore.php";
                        }
                    };
                    var s = "name=" + "<?php echo ($_SESSION["username"])?>" + "&points=" + points;
                    xmlhttp.open("POST", "highscore.php?name=", true);
                    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xmlhttp.send(s);
                }, delayInMilliseconds);
            }
        };
        //-------------------------------------------------------------------------------------------------------------------------

        //----------MAKING ENEMIES---------------------------------------------------------------------------------------------------------------
        enemies = this.physics.add.group();
        enemies.checkWorldBounds = true; 
        enemies.outOfBoundsKill = true;
        this.physics.add.overlap(this.player, enemies, hit, null, this); //Jos pelaaja osuu viholliseen, hakee hit funktion
        this.physics.add.collider(enemies, platforms);
        //----SHOOTING-------------------------------------------------------------------------------------------------------------------
        var Bullet = new Phaser.Class({

        Extends: Phaser.GameObjects.Image,
        initialize:
        function Bullet (scene)
        {
            Phaser.GameObjects.Image.call(this, scene, 0, 0, 'bullet');
            this.speed = Phaser.Math.GetSpeed(900, 1);
            this.checkWorldBounds = true;
            this.outOfBoundsKill = true;
            this.exists = false;
        },
        fire: function (x, y)
        {
            this.setPosition(x, y);
            this.setActive(true);
            this.setVisible(true);
        },
        update: function (time, delta)
        {
            this.x += this.speed * delta;

            if (this.x > 1200)
            {
                this.setActive(false);
                this.setVisible(false);
            }
        }
        });
        bullets = this.physics.add.group({
                classType: Bullet,
                maxSize: 15,
                runChildUpdate: true
            });
        //---------SHOOTKEY------------------------------------------------------------------------------------------------------
        spacebar = this.input.keyboard.addKey(Phaser.Input.Keyboard.KeyCodes.SPACE);
        //---------BULLET HITTING AN ENEMY------------------------------------------------------------------------------
        function got_em(bullet, enemy)
        {
            enemy.setActive(false);
            this.enemyhitsound.play();
            enemy.setVisible(false);
            enemy.disableBody(true, true);
            bullet.setActive(false);
            bullet.setVisible(false);
            bullet.setPosition(5555, 5555);
            this.score += 10;
            this.scoreText.setText('Points: ' + this.score); 
        }
        //this.physics.add.collider(bullets, enemies)
        this.physics.add.overlap(bullets, enemies, got_em, null, this);
        //------USING MOUSE TO PUSH ENEMIES AWAY---------------------------------------------------------------------------------------------
        this.input.on('gameobjectover', function (pointer, enemies) {
            enemies.setVelocity(Phaser.Math.Between(50, 500), (-200, 200));
        });  
        //-----BOSSES------------------------------------------------------------------------------------------------------------------
        boss = this.physics.add.group();
        this.physics.add.collider(boss, platforms);
        //BUllets hitting boss
        function bosshit(bullet, boss)
        {
            this.bosshp -= 1;
            this.enemyhitsound.play();
            this.bosshitpoints.setText('BossHP: ' + this.bosshp);
            boss.setVelocity(Phaser.Math.Between(-800, 800), (-200, 200));
            bullet.setActive(false);
            bullet.setVisible(false);
            bullet.setPosition(5555, 5555);
            if (this.bosshp < 1){
                this.bossisdead = 1;
                boss.setActive(false);
                boss.setVisible(false);
                boss.disableBody(true, true);
                this.score += 100;
                this.bosshitpoints.setText("He's dead Jim.");
                this.musiclove.stop();
                this.musicmega.stop();
                this.musicsea.stop();
                this.bossexplode.play();
                this.scoreText.setText('Points: ' + this.score);
                this.speakingtext.setText('WELL DONE!');
            }
        }
        this.physics.add.overlap(bullets, boss, bosshit, null, this);
        //---------TAKING A HIT FROM BOSS-----------------------------------------------------------------------
        function hitsfromboss (player, boss)
        {
            if (this.nohits < 1){
                this.hp -= 2;
                this.nohits = 60;
                this.hitpoints.setText('HP: ' + this.hp);
                boss.setVelocity(Phaser.Math.Between(200, 800), (-200, 200));
                this.gettinghitsound.play();
            }
            if(this.hp < 1) {
                player.setTint(0xff0000);
                var points = this.score;
                this.physics.pause();
                this.speakingtext.setText('DEATH!! \nYOU SCORED '+points+' POINTS!');
                this.bossexplode.play();
                //MUUTTUJIEN LÄHETYS
                var delayInMilliseconds = 5000; //5 second
                setTimeout(function() {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function(){
                        if(this.readyState==4){
                            window.location.href = "highscore.php";
                        }
                    };
                    var s = "name=" + "<?php echo ($_SESSION["username"])?>" + "&points=" + points;
                    xmlhttp.open("POST", "highscore.php?name=", true);
                    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xmlhttp.send(s);
                }, delayInMilliseconds);

            }
        };
        this.physics.add.collider(this.player, boss, hitsfromboss, null, this);
        //-----LIFE UP-----------------------------------------------------------------------------------------------------
        function lifeup (player, life){
            life.destroy();
            this.hp += 1;
            this.hitpoints.setText('HP: ' + this.hp);
        }
        life = this.physics.add.group();
        this.physics.add.collider(this.player, life, lifeup, null, this);
        //--------------SHIELDS hovering around player-----------------
        this.hoverpoint = new Phaser.Geom.Point(400, 300);
        // this.input.on('gameobjectmove', function (pointer) {
        // hoverpoint.setTo(this.player.x, this.player.y);
        // });
        //--------------SHIELDS-----------------
        function shieldhit (shield, enemy){
            shield.destroy();
            enemy.destroy();
            this.enemyhitsound.play();
            this.score += 15;
            this.scoreText.setText('Points: ' + this.score);
        }
        shield = this.physics.add.group();
        this.physics.add.overlap(shield, enemies, shieldhit, null, this);

        function shieldhitboss (shield, boss){
            shield.destroy();
            this.bosshp -= 10;
            this.bosshitpoints.setText('BossHP: ' + this.bosshp);
            this.enemyhitsound.play();
            if (this.bosshp < 1){
                this.bossisdead = 1;
                boss.setActive(false);
                boss.setVisible(false);
                boss.disableBody(true, true);
                this.score += 150;
                this.bosshitpoints.setText("He's dead Jim.");
                this.scoreText.setText('Points: ' + this.score);
                this.musiclove.stop();
                this.musicmega.stop();
                this.musicsea.stop();
                this.bossexplode.play();
                this.speakingtext.setText('WELL DONE!');
            }
            
        }
        this.physics.add.overlap(shield, boss, shieldhitboss, null, this);
        
        //------------WASD MOVEMENT--------------------------------------------------------------
        this.AKey = this.input.keyboard.addKey(Phaser.Input.Keyboard.KeyCodes.A);
        this.WKey = this.input.keyboard.addKey(Phaser.Input.Keyboard.KeyCodes.W);
        this.SKey = this.input.keyboard.addKey(Phaser.Input.Keyboard.KeyCodes.S);
        this.DKey = this.input.keyboard.addKey(Phaser.Input.Keyboard.KeyCodes.D);

        this.resetkey = this.input.keyboard.addKey(Phaser.Input.Keyboard.KeyCodes.R);
        //this.pausekey = this.input.keyboard.addKey(Phaser.Input.Keyboard.KeyCodes.P);

        //-----------MUSIC VARIABLES----------------
        this.musiclove =  this.sound.add('love');
        this.musiclove.loop = true;
        this.musicmega = this.sound.add('megaman');
        this.musicmega.loop = true;
        this.musicsea = this.sound.add('seagulls');
        this.musicsea.loop = true;


        } //END OF CREATE
        //-------------------------------------------------------------------------------------------------------------------------------
function update ()
{
    //-----------------------------MOVEMENT-------------------
  /*  if(this.pausekey.isDown){
        //this.scene.launch('sceneB');
        this.musiclove.stop();
        this.musicmega.stop();
        this.musicsea.stop();
        this.scene.pause();
    }*/

    if (this.AKey.isDown)
    {
        this.player.setVelocityX(-255);
        this.hoverpoint.setTo(this.player.x, this.player.y);
    }
    else if (this.DKey.isDown){
        this.player.setVelocityX(255);
        this.hoverpoint.setTo(this.player.x, this.player.y);
    }
    else {
        this.player.setVelocityX(0);
        this.hoverpoint.setTo(this.player.x, this.player.y);
    }
    if (this.WKey.isDown){
        this.player.setVelocityY(-255);
        this.hoverpoint.setTo(this.player.x, this.player.y);
    }
    else if (this.SKey.isDown)
    {
        this.player.setVelocityY(255);
        this.hoverpoint.setTo(this.player.x, this.player.y);
    }
    else
    {
        this.player.setVelocityY(0);
        this.hoverpoint.setTo(this.player.x, this.player.y);
    }

    if (this.resetkey.isDown){
        this.musiclove.stop();
        this.musicmega.stop();
        this.musicsea.stop();
        this.shootsound.play();
        this.scene.manager.bootScene(this);
        this.physics.resume();
    }

    //----------------SHOOTING-----------------------------------------------------------------
    if (Phaser.Input.Keyboard.JustDown(spacebar) && this.norungun < 1)
    {
        var bullet = bullets.get();
        if (bullet)
        {
            var x = this.player.x + 10;
            bullet.fire(x, this.player.y);
            this.norungun = 7;
            this.shootsound.play();
        }
    }
    //--------------RUNNING VARIABLES--------------------------------------------------------
    this.norungun -= 1; //PAUSE BETWEEN SHOTS
    this.nohits -= 1; //INVINCIBILITY PERIOD
    this.timer += 1; // TIMER FOR STUFF
    

    //----------------------ENEMY WAVE FUNCTIONS--------------------------------------------------------------------------
        function enemylooplines(i, a) { //-------STRAIGHT LINES--------
            setTimeout(function () { 
                var enem1 = Phaser.Math.Between(0, 2);  
                var skin = ' ';
                if (enem1 == 0){skin = 'enemy'; }
                else if (enem1 == 1){skin = 'enemy2'; }
                else if (enem1 == 2){skin = 'enemy3'; }
                enemies.create(1150, 95, skin).setInteractive().setVelocity(-250, 0).setBounce(1);
                enemies.create(1150, 200, skin).setInteractive().setVelocity(-250, 0).setBounce(1);
                enemies.create(1150, 300, skin).setInteractive().setVelocity(-250, 0).setBounce(1);
                enemies.create(1150, 400, skin).setInteractive().setVelocity(-250, 0).setBounce(1);
                enemies.create(1150, 500, skin).setInteractive().setVelocity(-250, 0).setBounce(1);
                enemies.create(1150, 550, skin).setInteractive().setVelocity(-250, 0).setBounce(1);
                enemies.create(1150, 600, skin).setInteractive().setVelocity(-250, 0).setBounce(1);
                i += 1;                    
                if (i < a) {           
                    setTimeout(enemylooplines(i, a), 300);            
                }                       
            }, 500)
        }//---------------------------------
        function enemyloop(i, a) { //--WAVE TO THE MIDDLE-----------
            setTimeout(function () { 
                var enem1 = Phaser.Math.Between(0, 2);  
                var skin = ' ';
                if (enem1 == 0){skin = 'enemy'; }
                else if (enem1 == 1){skin = 'enemy2'; }
                else if (enem1 == 2){skin = 'enemy3'; }
                   
                enemies.create(1000, 95, skin).setInteractive().setVelocity(-200, 50).setBounce(1);
                enemies.create(1000, 150, skin).setInteractive().setVelocity(-200, 25).setBounce(1);
                enemies.create(1000, 275, skin).setInteractive().setVelocity(-200, 13).setBounce(1);
                enemies.create(1000, 350, skin).setInteractive().setVelocity(-200, 0).setBounce(1);
                enemies.create(1000, 425, skin).setInteractive().setVelocity(-200, -13).setBounce(1);
                enemies.create(1000, 500, skin).setInteractive().setVelocity(-200, -25).setBounce(1);
                enemies.create(1000, 550, skin).setInteractive().setVelocity(-200, -50).setBounce(1);
                i += 1;                    
                if (i < a) {           
                    setTimeout(enemyloop(i, a), 300);            
                }                       
            }, 500)
        }//---------------------------------
        function enemyloopupper(i, a) { //--LINE TO UP-----------
            setTimeout(function () { 
                var enem1 = Phaser.Math.Between(0, 2);  
                var skin = ' ';
                if (enem1 == 0){skin = 'enemy'; }
                else if (enem1 == 1){skin = 'enemy2'; }
                else if (enem1 == 2){skin = 'enemy3'; }
                enemies.create(1000, 100, skin).setInteractive().setVelocity(-200, 0).setBounce(1);
                i += 1;                    
            if (i < a) {           
                setTimeout(enemyloopupper(i, a), 300);            
                }                       
            }, 500)
        }//---------------------------------------------
        function enemylooplower(i, a) { //--LINE TO DOWN----------
            setTimeout(function () { 
                var enem1 = Phaser.Math.Between(0, 2);  
                var skin = ' ';
                if (enem1 == 0){skin = 'enemy'; }
                else if (enem1 == 1){skin = 'enemy2'; }
                else if (enem1 == 2){skin = 'enemy3'; }
                enemies.create(1000, 666, skin).setInteractive().setVelocity(-200, 0).setBounce(1);
                i += 1;                    
            if (i < a) {           
                setTimeout(enemylooplower(i, a), 300);            
                }                       
            }, 500)
        }//-----------------------------------------
        function enemyloopzigzag(i, a) { //--MAYHEM----------
            setTimeout(function () { 
                var enem1 = Phaser.Math.Between(0, 2);  
                var skin = ' ';
                if (enem1 == 0){skin = 'enemy'; }
                else if (enem1 == 1){skin = 'enemy2'; }
                else if (enem1 == 2){skin = 'enemy3'; }
                enemies.create(1150, 100, skin).setInteractive().setVelocity(-200, 150).setBounce(1);
                enemies.create(1150, 175, skin).setInteractive().setVelocity(-200, 150).setBounce(1);
                enemies.create(1150, 250, skin).setInteractive().setVelocity(-200, 150).setBounce(1);
                enemies.create(1150, 325, skin).setInteractive().setVelocity(-200, 0).setBounce(1);
                enemies.create(1150, 475, skin).setInteractive().setVelocity(-200, -150).setBounce(1);
                enemies.create(1150, 500, skin).setInteractive().setVelocity(-200, -150).setBounce(1);
                enemies.create(1150, 550, skin).setInteractive().setVelocity(-200, -150).setBounce(1);
                i += 1;                    
            if (i < a) {           
                setTimeout(enemyloopzigzag(i, a), 300);            
                }                       
            }, 500)
        }//-----------------------------------------
        function enemylooprandom(i, a) { //--RANDOM----------
            setTimeout(function () { 
                var enem1 = Phaser.Math.Between(0, 2);  
                var skin = ' ';
                if (enem1 == 0){skin = 'enemy'; }
                else if (enem1 == 1){skin = 'enemy2'; }
                else if (enem1 == 2){skin = 'enemy3'; }
                enemies.create(1150, 100, skin).setInteractive().setVelocity(Phaser.Math.Between(-600, -150), (25, 500)).setBounce(1);
                enemies.create(1150, 250, skin).setInteractive().setVelocity(Phaser.Math.Between(-600, -150), (15, 200)).setBounce(1);
                enemies.create(1150, 350, skin).setInteractive().setVelocity(Phaser.Math.Between(-600, -150), 0).setBounce(1);
                enemies.create(1150, 500, skin).setInteractive().setVelocity(Phaser.Math.Between(-600, -150), (-15, -200)).setBounce(1);
                enemies.create(1150, 550, skin).setInteractive().setVelocity(Phaser.Math.Between(-600, -150), (-25, -500)).setBounce(1);
                i += 1;                    
            if (i < a) {           
                setTimeout(enemylooprandom(i, a), 300);            
                }                       
            }, 500)
        }//-----------------------------------------

    //------------Waves of enemies------------------------------------------------------------------------------------
    //Wave 1
    if (this.setdifficulty <= 3){
        if (this.timer == 120){enemylooplower(this.difficulty, this.setdifficulty + 5);}
        if (this.timer == 280){enemyloopupper(this.difficulty, this.setdifficulty +5);}
        if (this.timer == 360){enemyloop(this.difficulty, this.setdifficulty);}
        if (this.timer == 600){enemylooprandom(this.difficulty, this.setdifficulty);}
        if (this.timer == 840){enemyloopzigzag(this.difficulty, this.setdifficulty);}
        if (this.timer == 1200){enemylooplines(this.difficulty, this.setdifficulty);}
        if (this.timer == 1440){enemyloop(this.difficulty, this.setdifficulty);}
        if (this.timer == 1800){enemylooplines(this.difficulty, this.setdifficulty);}
        if (this.timer == 2100){enemylooprandom(this.difficulty, this.setdifficulty);}
        
        if (this.timer == 2280){
            boss.create(1000, 350, 'boss').setVelocity(Phaser.Math.Between(300, 700), 20).setBounce(1).setCollideWorldBounds(true);
            this.bosshp = 10 * this.setdifficulty;
            this.bosshitpoints.setText('BossHP: ' + this.bosshp);
            this.gamecompleted += 1;
        }
    }
    //Wave 2

    else if (this.setdifficulty >= 4 && this.setdifficulty <= 5){
        if (this.timer == 120){enemyloopupper(this.difficulty, this.setdifficulty + 5);}
        if (this.timer == 120){enemylooplower(this.difficulty, this.setdifficulty +5);}
        if (this.timer == 360){enemyloop(this.difficulty, this.setdifficulty);}
        if (this.timer == 600){enemylooplines(this.difficulty, this.setdifficulty);}
        if (this.timer == 840){enemyloop(this.difficulty, this.setdifficulty);}
        if (this.timer == 1200){enemylooplines(this.difficulty, this.setdifficulty);}
        if (this.timer == 1440){enemyloop(this.difficulty, this.setdifficulty);}
        if (this.timer == 1800){enemyloopzigzag(this.difficulty, this.setdifficulty);}
        if (this.timer == 2100){enemylooprandom(this.difficulty, this.setdifficulty);}
        if (this.timer == 2220){enemylooprandom(this.difficulty, this.setdifficulty);}
        if (this.timer == 2440){enemylooprandom(this.difficulty, this.setdifficulty);}
        if (this.timer == 2880){enemyloopupper(this.difficulty, this.setdifficulty + 8);}
        if (this.timer == 3100){enemylooplower(this.difficulty, this.setdifficulty + 8);}
        if (this.timer == 3300){enemylooplines(this.difficulty, this.setdifficulty + 2);}
        
        if (this.timer == 3900){
            boss.create(1100, 350, 'boss').setVelocity(Phaser.Math.Between(200, 600), 80).setBounce(1).setCollideWorldBounds(true);
            this.bosshp = 10 * this.setdifficulty;
            this.bosshitpoints.setText('BossHP: ' + this.bosshp);
        }
    }
    //Wave 3
    else if (this.setdifficulty >= 6 && this.setdifficulty <= 7){
        if (this.timer == 120){enemylooplower(this.difficulty, this.setdifficulty + 5);}
        if (this.timer == 120){enemyloopupper(this.difficulty, this.setdifficulty +5);}
        if (this.timer == 360){enemyloop(this.difficulty, this.setdifficulty);}
        if (this.timer == 600){enemylooprandom(this.difficulty, this.setdifficulty);}
        if (this.timer == 840){enemyloopzigzag(this.difficulty, this.setdifficulty);}
        if (this.timer == 1200){enemylooplines(this.difficulty, this.setdifficulty);}
        if (this.timer == 1440){enemyloop(this.difficulty, this.setdifficulty);}
        if (this.timer == 1800){enemylooplines(this.difficulty, this.setdifficulty);}
        if (this.timer == 2100){enemylooprandom(this.difficulty, this.setdifficulty);}
        if (this.timer == 2300){enemylooplower(this.difficulty, this.setdifficulty + 5);}
        if (this.timer == 2300){enemyloopupper(this.difficulty, this.setdifficulty +5);}
        if (this.timer == 2700){enemyloop(this.difficulty, this.setdifficulty);}
        if (this.timer == 2900){enemylooprandom(this.difficulty, this.setdifficulty);}
        if (this.timer == 3100){enemyloopzigzag(this.difficulty, this.setdifficulty);}
        if (this.timer == 3300){enemylooplines(this.difficulty, this.setdifficulty);}
        if (this.timer == 3500){enemyloop(this.difficulty, this.setdifficulty);}
        if (this.timer == 3700){enemylooplines(this.difficulty, this.setdifficulty);}
        if (this.timer == 3900){enemylooprandom(this.difficulty, this.setdifficulty);}
        if (this.timer == 4400){enemylooplower(this.difficulty, this.setdifficulty + 5);}
        if (this.timer == 4400){enemyloopupper(this.difficulty, this.setdifficulty +5);}
        if (this.timer == 4600){enemyloop(this.difficulty, this.setdifficulty);}
        if (this.timer == 4800){enemylooprandom(this.difficulty, this.setdifficulty);}
        if (this.timer == 5000){enemyloopzigzag(this.difficulty, this.setdifficulty);}
        if (this.timer == 5200){enemylooplines(this.difficulty, this.setdifficulty);}
        if (this.timer == 5440){enemyloop(this.difficulty, this.setdifficulty);}
        if (this.timer == 5660){enemylooplines(this.difficulty, this.setdifficulty);}
        if (this.timer == 5880){enemylooprandom(this.difficulty, this.setdifficulty);}
        
        if (this.timer == 6280){
            boss.create(1100, 350, 'finalboss').setVelocity(Phaser.Math.Between(200, 600), 20).setBounce(1).setCollideWorldBounds(true);
            this.bosshp = 15 * this.setdifficulty;
            this.bosshitpoints.setText('BossHP: ' + this.bosshp);
            this.gamecompleted += 1;
        }
    }


    //-----START THE WAVES OVER-----

    if (this.bossisdead == 1){
        this.timer = 0;
        this.setdifficulty += 1;
        this.bossisdead = 0;
        this.trackselect += 1;
    }

    //MUSIC------------------
    //-----------TRACK 1-----------------
    if (this.trackselect == 0 && this.play ==0){
        this.musiclove.play();
        this.play += 1;
    }
    if (this.trackselect == 1 && this.play ==1){
        this.musiclove.play();
        this.play += 1;
    }
    //--------TRACK 2----------------
    if (this.trackselect == 2 && this.play ==2){
        this.musicmega.play();
        this.play += 1;
    }
    if (this.trackselect == 3 && this.play ==3){
        this.musicmega.play();
        this.play += 1;
    }
    //------------TRACK 3 --------
    if (this.trackselect == 4 && this.play ==4){
        this.musicsea.play();
        this.play += 1;
    }
    if (this.trackselect == 5 && this.play ==5){
        this.musicsea.play();
        this.play += 1;
    }

    //---------LIVES------------------------------------------------------
    if (this.timer == 300 || this.timer == 1300 || this.timer == 2200 || this.timer == 5000 || this.timer == 7000){
        life.create(900, (Phaser.Math.Between(100, 650)), 'life').setInteractive().setVelocityX(Phaser.Math.Between(-500, -100)).setBounce(1);
    }
    //-------ROTATING SHIELDS-----------------------------------------------------------
    Phaser.Actions.RotateAroundDistance(shield.getChildren(), this.hoverpoint, 0.1, 50);
    if (this.score >= 100 && this.score <= 310 && this.shieldtimes == 0){
        shield.create(this.player.x, this.player.y, 'shield');
        this.shieldtimes = 1;
    }
    if (this.score >= 500 && this.score <= 710 && this.shieldtimes == 1){
        shield.create(this.player.x, this.player.y, 'shield');
        this.shieldtimes = 2;
    }
    if (this.score >= 1000 && this.score <= 1210 && this.shieldtimes == 2){
        shield.create(this.player.x, this.player.y, 'shield');
        this.shieldtimes = 3;
    }
    if (this.score >= 1500 && this.score <= 1710 && this.shieldtimes == 3){
        shield.create(this.player.x, this.player.y, 'shield');
        this.shieldtimes = 4;
    }
    if (this.score >= 4200 && this.score <= 4410 && this.shieldtimes == 4){
        shield.create(this.player.x, this.player.y, 'shield');
        shield.create(this.player.x, this.player.y, 'shield');
        this.shieldtimes = 5;
    }



    //---------------MESSAGES-------------------------------------------------------
    if (this.timer == 190 && this.setdifficulty < 8 || this.timer == 0 ){
        this.speakingtext.setText('');
    }
    if (this.timer == 10 && this.setdifficulty ==2){
        this.speakingtext.setText('WASD to move \nSpace to shoot!');
    }
    if (this.timer == 10 && this.setdifficulty == 3){
        this.speakingtext.setText('Pew Pew Pew!');
    }
    if (this.timer == 10 && this.setdifficulty == 4){
        this.speakingtext.setText("Heyy, you're pretty good!");
    }
    if (this.timer == 10 && this.setdifficulty == 5){
        this.speakingtext.setText('Time to get SERIOUS!');
    }
    if (this.timer == 10 && this.setdifficulty == 6){
        this.speakingtext.setText("You're almost there!!");
    }
    if (this.timer == 10 && this.setdifficulty == 7){
        this.speakingtext.setText('FINAL WAVES!');
    }
    if (this.timer == 10 && this.setdifficulty == 8){
        this.speakingtext.setText('HEYOO YOU BEAT THE GAME M8');
    }
    if (this.timer == 130 && this.setdifficulty == 8){
        this.speakingtext.setText("I can't believe \nyou've done this");
    }
    if (this.timer == 460 && this.setdifficulty == 8){
        this.speakingtext.setText('What now?');
    }
    if (this.timer == 800 && this.setdifficulty == 8){
        this.speakingtext.setText('Maybe play again..?');
    }
    if (this.timer == 900 && this.setdifficulty == 8){
        this.speakingtext.setText('You know you want to');
    }
    if (this.timer == 1500 && this.setdifficulty == 8){
        this.speakingtext.setText('WATCH OUT!');
        enemies.create(1000, 350, 'finalboss').setInteractive().setVelocity(Phaser.Math.Between(-300, -50), 0).setBounce(1);
    }
    if (this.timer == 2000 && this.setdifficulty == 8){
        this.speakingtext.setText('That bastard was still alive!');
    }
    if (this.timer == 2400 && this.setdifficulty == 8){
        this.speakingtext.setText("OK now the games's over");
    }
    if (this.timer == 2800 && this.setdifficulty == 8){
        var points = this.score;
        if(this.hp > 0){
            points = points*this.hp;
        }
        this.speakingtext.setText('CONGRATULATIONS! \nYOU SCORED '+points+' POINTS!');
        var delayInMilliseconds = 5000; //5 second
        setTimeout(function() {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(this.readyState==4){
                    window.location.href = "highscore.php";
                }
            };
            var s = "name=" + "<?php echo ($_SESSION["username"])?>" + "&points=" + points;
            xmlhttp.open("POST", "highscore.php?name=", true);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send(s);
        }, delayInMilliseconds);
    }

    //BEATING THE GAME-----------------------------------------------------------
    if (this.gamecompleted == 7 && this.bosshp < 1){
        var points = this.score;
        if(this.hitpoint>0){
            points = points*this.score;
        }
        this.bosshp = 5000;
        console.log('gamefinished');
        this.speakingtext.setText('CONGRATULATIONS! YOU SCORED '+points+' POINTS!');
        this.player.setTint(0xffdd00);
        this.backgroundimage = this.add.image(600, 350, 'gamewin');
        //MUUTTUJIEN LÄHETYS
        var delayInMilliseconds = 5000; //5 second
        setTimeout(function() {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(this.readyState==4){
                    window.location.href = "highscore.php";
                }
            };
            var s = "name=" + "<?php echo ($_SESSION["username"])?>" + "&points=" + points;
            xmlhttp.open("POST", "highscore.php", true);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send(s);
        }, delayInMilliseconds);
    }






} //END OF UPDATE


</script>