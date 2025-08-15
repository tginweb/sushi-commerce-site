<template>
  <q-page class="favorites-page">
    <div class="container">
      <!-- Пустое состояние -->
      <div v-if="!favorites.length" class="empty-state text-center q-pa-xl">
        <q-icon
          name="favorite_border"
          size="80px"
          color="grey-4"
          class="q-mb-md"
        />
        <h3 class="text-h5 text-weight-medium q-mb-sm">В избранном пока ничего нет</h3>
        <p class="text-grey-6 q-mb-lg">
          Добавляйте товары в избранное, чтобы быстро находить их позже
        </p>
        <q-btn
          color="primary"
          label="Перейти в каталог"
          to="/catalog"
          unelevated
          size="lg"
        />
      </div>

      <!-- Список избранных товаров -->
      <div v-else class="favorites-grid">
        <div class="row q-col-gutter-md">
          <div
            v-for="product in favorites"
            :key="product.ID"
            class="col-12 col-sm-6 col-md-4 col-lg-3"
          >
            <ProductCardGrid
              :product="product"
              view-mode="grid"
              orientation="vert"
              border-style="all"
            />
          </div>
        </div>
      </div>

      <!-- Кнопка очистки избранного -->
      <div v-if="favorites.length" class="text-center q-mt-xl">
        <q-btn
          color="grey-6"
          label="Очистить избранное"
          outline
          @click="clearFavorites"
          :loading="clearing"
        />
      </div>
    </div>
  </q-page>
</template>

<script setup lang="ts">
import {ref} from "vue";
import {useShopFavStore} from "@/modules/shop/store/fav";
import {storeToRefs} from "pinia";
import ProductCardGrid from "@/components/Product/ProductCardGrid.vue";

const favStore = useShopFavStore();
const {favorites, favoritesCount} = storeToRefs(favStore);

const clearing = ref(false);

const clearFavorites = async () => {
  clearing.value = true;

  try {
    // Удаляем все товары из избранного
    const productIds = Object.keys(favStore.favProductIds);
    for (const productId of productIds) {
      await favStore.favToggle(parseInt(productId));
    }
  } catch (error) {
    console.error('Ошибка при очистке избранного:', error);
  } finally {
    clearing.value = false;
  }
};
</script>

<style scoped lang="scss">
.favorites-page {
  background-color: #f8f9fa;
  min-height: 100vh;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 24px;
}

.page-header {
  background: white;
  padding: 24px;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.empty-state {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  min-height: 400px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.favorites-grid {
  .row {
    margin: 0;
  }
}

@media (max-width: 599px) {
  .container {
    padding: 16px;
  }

  .page-header {
    padding: 16px;
  }

  .empty-state {
    padding: 32px 16px;
  }
}
</style>
