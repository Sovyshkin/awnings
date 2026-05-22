<template>
  <header class="header" :class="{ 'menu-open': menuStore.isOpen, 'dark': darkHeader }">
      <div class="wrap-menu" @click="menuStore.toggle()">
        <div class="menu">
          <div class="frame frame-1" :class="{ 'active': menuStore.isOpen }"></div>
          <div class="frame frame-2" :class="{ 'active': menuStore.isOpen }"></div>
          <div class="frame frame-3" :class="{ 'active': menuStore.isOpen }"></div>
        </div>
      </div>
      <router-link to="/catalog" class="btn">Каталог</router-link>
  </header>
  <Transition name="slide-down">
    <OpenMenu v-if="menuStore.isOpen" />
  </Transition>
</template>

<script setup>
import { useMenuStore } from '../stores/menuStore';
import OpenMenu from './OpenMenu.vue';

defineProps({
  darkHeader: {
    type: Boolean,
    default: false
  }
})
const menuStore = useMenuStore();
</script>

<style scoped>

.header {
  position: fixed;
  width: calc(100% - 80px);
  top: 34px;
  left: 40px;
  z-index: 101;
  box-shadow: 0px 4px 4px 0px #00000040;
  backdrop-filter: blur(20.899999618530273px);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px;
  border-radius: 44px;
  transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease, backdrop-filter 0.3s ease;
}

.header.menu-open {
  box-shadow: 0px 4px 4px 0px #00000040;
  background-color: rgba(255, 255, 255, 0.073);
  backdrop-filter: blur(20px);
}

.header.dark {
  background-color: #383838;
}

.header.dark .frame {
  background-color: #fff;
}

.header.dark .btn {
  background-color: #C96744;
}

.header.dark .btn:hover {
  background-color: #b55a3a;
}

.header.dark .wrap-menu {
  background-color: #4a4a4a;
}

.header > .wrap-menu,
.header > .btn {
  position: relative;
  z-index: 102;
}

.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.5s ease;
}

.slide-down-enter-from,
.slide-down-leave-to {
  opacity: 0;
  transform: translateY(-100%);
}

.header:hover {
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

.frame-1.active {
  transform: translateY(8px) rotate(45deg);
}

.frame-2.active {
  opacity: 0;
}

.frame-3.active {
  transform: translateY(-8px) rotate(-45deg);
}

.btn {
  background-color: #000;
  border-radius: 44px;
  width: 196px;
  height: 56px;
  color: #FFFFFF;
  font-weight: 600;
  font-size: 16px;
  border: none;
  cursor: pointer;
  text-decoration: none;
  display: flex;
  align-items: center;
  justify-content: center;
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
    gap: 5px;
  }

  .frame {
    width: 26px;
    height: 2px;
  }

  .frame-1.active {
    transform: translateY(7px) rotate(45deg);
  }

  .frame-3.active {
    transform: translateY(-7px) rotate(-45deg);
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

  .frame-1.active {
    transform: translateY(6px) rotate(45deg);
  }

  .frame-3.active {
    transform: translateY(-6px) rotate(-45deg);
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
    width: 90px;
    height: 36px;
    font-size: 11px;
    padding: 0;
  }

  .menu {
    padding: 10px 14px;
    gap: 4px;
  }

  .frame {
    width: 18px;
    height: 2px;
  }

  .frame-1.active {
    transform: translateY(6px) rotate(45deg);
  }

  .frame-3.active {
    transform: translateY(-6px) rotate(-45deg);
  }
}
</style>
