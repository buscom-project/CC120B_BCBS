<template>
    <div class="credit-card-container">
        <div class="credit-card" :class="{ flipped: props.isFlipped }">
        <!-- Front -->
        <div class="card card-front">
          <div class="card-number">{{ formattedCardNumber }}</div>
          <div class="card-name">{{ cardName || 'FULL NAME' }}</div>
          <div class="card-expiry">
            <span>VALID THRU </span>
            <span>{{ cardExpiry || 'MM/YY' }}</span>
          </div>
        </div>
  
        <!-- Back -->
        <div class="card card-back">
          <div class="stripe" />
          <div class="cvv">{{ cardCVV || '***' }}</div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref, watch, computed } from 'vue';
  
  const props = defineProps<{
    cardNumber: string;
    cardName: string;
    cardExpiry: string;
    cardCVV: string;
    isFlipped: boolean;
    }>();
  
  const isFlipped = ref(false);
  
  
  // Format card number in groups of 4, max 16 digits
  const formattedCardNumber = computed(() => {
    const digitsOnly = props.cardNumber.replace(/\D/g, '').slice(0, 16);
    const padded = digitsOnly.padEnd(16, '*');
    return padded.replace(/(.{4})/g, '$1 ').trim();
  });
  </script>
  
  <style scoped>
  .credit-card-container {
    perspective: 1000px;
    width: 260px;
    margin: auto;
  }
  .credit-card {
    width: 100%;
    height: 150px;
    transition: transform 0.6s;
    transform-style: preserve-3d;
    position: relative;
  }
  .credit-card.flipped {
    transform: rotateY(180deg);
  }
  .card {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 12px;
    backface-visibility: hidden;
    padding: 15px;
    color: white;
    font-family: 'Courier New', Courier, monospace;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  }
  .card-front {
    background: linear-gradient(135deg, #667eea, #764ba2);
  }
  .card-back {
    background: #333;
    transform: rotateY(180deg);
  }
  .card-number {
    font-size: 1rem;
    margin-top: 30px;
    letter-spacing: 2px;
  }
  .card-name {
    margin-top: 20px;
    font-size: 0.85rem;
  }
  .card-expiry {
    font-size: 0.75rem;
    margin-top: 10px;
  }
  .stripe {
    height: 30px;
    background: #000;
    margin-top: 25px;
  }
  .cvv {
    margin-top: 15px;
    text-align: right;
    font-size: 1rem;
    letter-spacing: 2px;
  }
  </style>
  