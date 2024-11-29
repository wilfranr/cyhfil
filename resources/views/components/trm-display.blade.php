<style>
  @keyframes marquee {
      0% {
          transform: translateX(100%);
      }
      100% {
          transform: translateX(-100%);
      }
  }
  
  .animate-marquee {
      display: inline-block;
      animation: marquee 3s linear infinite; /* Velocidad ajustada a 5s */
      white-space: nowrap;
  }
  .animate-marquee:hover {
    animation-play-state: paused;
}

  </style>
  
  <div class="overflow-hidden whitespace-nowrap">
      <div class="inline-block animate-marquee">
          TRM: <span class="font-bold">${{ number_format($trm, 2) }}</span>
      </div>
  </div>
  