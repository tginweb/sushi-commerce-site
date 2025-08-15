<template>

  <div ref="com" class="com relative-position">


    <q-select
      ref="input"
      v-model="content"
      :loading="isLoading"
      :options="options"
      behavior="default"
      bg-color="white"
      class="c-search"
      color="dark"
      fill-input
      filled
      hide-dropdown-icon
      hide-selected
      input-debounce="1000"
      placeholder="Поиск по товарам"
      style="position: relative; z-index: 23"
      use-input
      @filter="filterFn"
      @focus="onFocus"
      @input-value="onInputValue"
    >

      <template v-if="total > 12 || true" v-slot:after-options>

        <q-btn
          :to="'/catalog/search/?text=' + content"
          class="full-width q-py-sm"
          color="primary"
          flat
          label="показать все результаты"
        />

      </template>

      <template v-slot:option="ctx">

        <catalog-product-element-search
          :item="ctx.opt.row"
        />

      </template>

      <template v-slot:prepend>
        <q-icon name="search" size="28px"/>
      </template>

      <template v-if="haveCloser" v-slot:append>
        <q-icon
          :name="$icons.close"
          class="cursor-pointer"
          size="20px"
          @click="valueState = false"
        />
      </template>

    </q-select>

    <div v-if="focus && historyItems.length && !content" class="c-history bg-white">

      <div class="flex s-font-2xs q-pt-md q-pb-sm q-px-md items-center">
        <div class="">
          История запросов
        </div>
        <div class="q-ml-auto">
          <span class="text-primary cursor-pointer"  @click="clearHistory">очистить</span>
        </div>
      </div>

      <div
        v-for="(item, index) of historyItems"
        :key="index"
        class="q-py-sm q-px-md text-primary-brown-gray-4 cursor-pointer"
        @click="onSelectHistory(item)"
      >
        {{item}}
      </div>

    </div>

    <div
      v-if="focus"
      style="position: fixed; left: 0; top: 0; width: 100vw; height: 100vh; z-index: 1; background-color: rgba(0,0,0, 0.5);"
      @click="focus=false"
    >
    </div>

  </div>

</template>

<script>

export default {
  apollo: {},
  props: {
    value: {},

    haveCloser: {},
    searchInput: String,
    label: {},
    outlined: {default: false},
    filled: {default: false},
    placeholder: {},
    disabled: Boolean,
    rules: {default: () => []},
    errorMessages: {default: () => []},
    fromBound: {},
    toBound: {},
    valueData: {},
    location: {},
  },
  data() {
    return {
      valueState: this.value,

      focus: false,
      searchText: '',
      isLoading: false,
      search: this.searchInput,
      selectedAddress: null,
      content: '',
      queryToken: null,
      queryAddressToken: null,
      options: [],
      total: null,

      queries: {
        elements: {
          vars: {
            filter: {},
            nav: {limit: 5},
          },
          state: {
            isLoading: false,
            skip: true
          },
          result: null
        },
      },

      lastSuccessQuery: null,
      addHistoryTimeout: null,

      historyItems: []
    }
  },

  watch: {
    valueState(val) {
      this.$emit('input', val)

      if (!val)
        this.focus = false
    },
    value(val) {
      this.valueState = val
    },
    lastSuccessQuery(val) {

      if (val) {

        if (this.addHistoryTimeout)
          clearTimeout(this.addHistoryTimeout)

        this.addHistoryTimeout = setTimeout(() => {
          this.addHistoryItem(val)
        }, 3000)
      }
    }
  },

  methods: {

    onSelectHistory(text) {

      this.$refs.input.focus()

      setTimeout(() => {
        this.content = text
        this.$refs.input.filter(text)
        this.$refs.input.updateInputValue(text)
      }, 30)

    },

    loadHistoryItems() {
      this.historyItems = this.$q.cookies.get('SEARCH_HISTORY_ITEMS') || []
    },
    addHistoryItem(query) {

      if (!query) return
      const items = this.$q.cookies.get('SEARCH_HISTORY_ITEMS') || []
      items.unshift(query)
      items.length = 5
      this.$q.cookies.set('SEARCH_HISTORY_ITEMS', items, {expires: 10})
      this.loadHistoryItems()
    },
    clearHistory() {
      this.$q.cookies.set('SEARCH_HISTORY_ITEMS', [], {expires: 10})
      this.loadHistoryItems()
    },

    onInputValue(val) {
      this.content = val
    },

    onFocus() {
      this.valueState = true
      this.focus = true
    },

    async filterFn(query, update, abort) {

      if (!query) {
        abort();
        return;
      }

      try {

        const {data} = await this.$apollo.query(
          {
            query: require('~module/catalog/gql/product/query/product_recordset.gql'),
            variables: {
              filter: {
                SEARCH: {like: query}
              },
              nav: {
                limit: 12
              }
            }
          }
        )

        let options = [];

        if (data.res && data.res.nodes) {

          options = data.res.nodes.map(item => ({
            label: item.NAME,
            value: item.URL,
            row: item
          }))

          this.lastSuccessQuery = query
        }

        update(() => {
          this.options = options
          this.total = data.res.info.total
        })

      } catch (e) {
        console.log(e)
        abort();
      }

    },

  },
  mounted() {
    this.loadHistoryItems()
  }
};
</script>

<style lang="scss" scoped>

.c-search {
  /deep/ {
    .q-field__control {
      border-radius: 4px;
    }

    .q-field__control,
    .q-field__marginal,
    .q-field__native {
      height: 40px;
      min-height: auto;
    }

    .q-field__control:after, .q-field__control:before {
      display: none;
    }
  }
}

.c-history {
  position: absolute;
  left: 0;
  width: 100%;
  top: 100%;
  z-index: 10;
}

</style>
