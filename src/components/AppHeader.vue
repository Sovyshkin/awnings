<template>
  <header class="header">
      <div class="wrap-menu">
        <div class="menu">
          <div class="frame frame-1"></div>
          <div class="frame frame-2"></div>
          <div class="frame frame-3"></div>
        </div>
      </div>
      <button class="btn">Каталог</button>
  </header>
</template>

<script setup>
</script>

<style scoped>

.header {
  position: fixed;
  width: calc(100% - 80px);
  top: 34px;
  left: 40px;
  z-index: 100;
  box-shadow: 0px 4px 4px 0px #00000040;
  backdrop-filter: blur(20.899999618530273px);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px;
  border-radius: 44px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.header:hover {
  box-shadow: 0px 8px 20px 0px #00000050;
  transform: translateY(-2px);
}

.wrap-menu {
  background-color: #FFFFFF;
  border-radius: 44px;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.wrap-menu:hover {
  background-color: #f5f5f5;
  transform: scale(1.02);
}

.menu {
  display: flex;
  flex-direction: column;
  gap: 6px;
  padding: 19px 26px;
}

.frame {
  width: 30px;
  height: 2px;
  background-color: #000000;
  border-radius: 16px;
  transition: transform 0.3s ease, opacity 0.3s ease;
}

.wrap-menu:hover .frame-1 {
  transform: translateY(4px) rotate(5deg);
}

.wrap-menu:hover .frame-3 {
  transform: translateY(-4px) rotate(-5deg);
}

.btn {
  background-color: #000;
  border-radius: 44px;
  width: 196px;
  height: 56px;
  color: #FFFFFF;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

.btn:hover {
  background-color: #333;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.btn:active {
  transform: translateY(0);
}

@media (max-width: 1024px) {
  .header {
    width: calc(100% - 48px);
    left: 24px;
    top: 20px;
    padding: 16px 20px;
    border-radius: 36px;
  }

  .btn {
    width: 160px;
    height: 48px;
    font-size: 14px;
  }

  .menu {
    padding: 15px 22px;
  }

  .frame {
    width: 26px;
    height: 2px;
  }
}

@media (max-width: 768px) {
  .header {
    width: calc(100% - 32px);
    left: 16px;
    top: 16px;
    padding: 12px 16px;
    border-radius: 30px;
  }

  .btn {
    width: 120px;
    height: 40px;
    font-size: 12px;
    border-radius: 30px;
  }

  .menu {
    padding: 12px 18px;
    gap: 5px;
  }

  .frame {
    width: 22px;
    height: 2px;
  }
}

@media (max-width: 480px) {
  .header {
    width: calc(100% - 24px);
    left: 12px;
    top: 12px;
    padding: 10px 14px;
    border-radius: 24px;
  }

  .btn {
    width: 100px;
    height: 36px;
    font-size: 11px;
    padding: 0 12px;
  }

  .menu {
    padding: 10px 14px;
    gap: 4px;
  }

  .frame {
    width: 18px;
    height: 2px;
  }
}
</style>
