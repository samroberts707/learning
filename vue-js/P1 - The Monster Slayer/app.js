new Vue({
  el: '#app',
  data: {
    playerHealth: 100,
    monsterHealth: 100,
    gameIsRunning: false,
  },
  methods: {
    calcDamage: function(min,max) {
      return Math.max(Math.floor(Math.random() * max) + 1, min);
    },
    startGame: function() {
      this.playerHealth = 100;
      this.monsterHealth = 100;
      this.gameIsRunning = true;
    },
    attack: function() {
      this.monsterHealth -= this.calcDamage(3,10);

      if(this.monsterHealth <= 0) {
        alert('You Won!');
        this.gameIsRunning = false;
        return;
      }

      this.playerHealth -= this.calcDamage(5,12);

      if(this.playerHealth <= 0) {
        alert('You Lost!');
        this.gameIsRunning = false;
        return;
      }

    },
    specialAttack: function() {

    },
    heal: function() {

    },
    giveUp: function() {

    },
  }
});
