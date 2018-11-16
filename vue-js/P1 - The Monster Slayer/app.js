new Vue({
  el: '#app',
  data: {
    playerHealth: 100,
    monsterHealth: 100,
    gameIsRunning: false,
  },
  methods: {
    // Start game method to initialize variables
    startGame: function() {
      this.playerHealth = 100;
      this.monsterHealth = 100;
      this.gameIsRunning = true;
    },
    // Basic Attack against monster
    attack: function() {
      this.monsterHealth -= this.calcDamage(3,10);
      if(this.checkWin()) {
        return;
      }
      this.monsterAttack();
    },
    // Special Attack against monster with increased damage
    specialAttack: function() {
      this.monsterHealth -= this.calcDamage(10,20);
      if(this.checkWin()) {
        return;
      }
      this.monsterAttack();
    },
    // Heal the player for 10, capped at 100 health points
    heal: function() {
      if(this.playerHealth <= 90) {
        this.playerHealth += 10;
      } else {
        this.playerHealth = 100;
      }
      this.monsterAttack();
    },
    // Set game running to false to force a game reset
    giveUp: function() {
      this.gameIsRunning = false;
    },
    // Calculate the random amount of damage to be applied
    calcDamage: function(min,max) {
      return Math.max(Math.floor(Math.random() * max) + 1, min);
    },
    // Check if the player has won or lost
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
    // Monster attacks the player
    monsterAttack: function() {
      this.playerHealth -= this.calcDamage(5,12);
      this.checkWin();
    },
  }
});
