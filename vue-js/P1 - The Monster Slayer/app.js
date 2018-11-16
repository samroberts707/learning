new Vue({
  el: '#app',
  data: {
    playerHealth: 100,
    monsterHealth: 100,
    gameIsRunning: false,
  },
  methods: {
    startGame: function() {
      this.playerHealth = 100;
      this.monsterHealth = 100;
      this.gameIsRunning = true;
    },
    attack: function() {
      this.monsterHealth -= this.calcDamage(3,10);
      if(this.checkWin()) {
        return;
      }
      this.monsterAttack();
    },
    specialAttack: function() {
      this.monsterHealth -= this.calcDamage(10,20);
      if(this.checkWin()) {
        return;
      }
      this.monsterAttack();
    },
    heal: function() {

    },
    giveUp: function() {

    },
    calcDamage: function(min,max) {
      return Math.max(Math.floor(Math.random() * max) + 1, min);
    },
    checkWin: function() {
      if(this.monsterHealth <= 0) {
        if(confirm('You won! New Game')){
          this.startGame();
        } else {
          this.gameIsRunning = false;
        }
        return true;
      } else if (this.playerHealth <= 0) {
        if(confirm('You Lost! New Game')){
          this.startGame();
        } else {
          this.gameIsRunning = false;
        }
        return true;
      }
      return false;
    },
    monsterAttack: function() {
      this.playerHealth -= this.calcDamage(5,12);
      this.checkWin();
    },
  }
});
