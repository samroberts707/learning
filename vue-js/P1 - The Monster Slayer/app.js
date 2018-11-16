new Vue({
  el: '#app',
  data: {
    playerHealth: 100,
    monsterHealth: 100,
    gameIsRunning: false,
    turns: [],
  },
  methods: {
    // Start game method to initialize variables
    startGame: function() {
      this.playerHealth = 100;
      this.monsterHealth = 100;
      this.gameIsRunning = true;
      this.turns = [];
    },
    // Basic Attack against monster
    attack: function() {
      var playerDamage = this.calcDamage(3,10)
      this.monsterHealth -= playerDamage;
      this.turns.unshift({
        isPlayer: true,
        text: 'Player hits monster for ' + playerDamage,
      });
      if(this.checkWin()) {
        return;
      }
      this.monsterAttack();
    },
    // Special Attack against monster with increased damage
    specialAttack: function() {
      var playerDamage = this.calcDamage(10,20)
      this.monsterHealth -= playerDamage;
      this.turns.unshift({
        isPlayer: true,
        text: 'Player hits monster hard for ' + playerDamage,
      });
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
      this.turns.unshift({
        isPlayer: true,
        text: 'Player heals for 10',
      });
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
      var monsterDamage = this.calcDamage(5,12)
      this.playerHealth -= monsterDamage;
      this.turns.unshift({
        isPlayer: false,
        text: 'Monster hits player for ' + monsterDamage,
      });
      this.checkWin();
    },
  }
});
