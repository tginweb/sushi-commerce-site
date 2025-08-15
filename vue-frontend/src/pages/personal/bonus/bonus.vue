<template>

  <component
      :path="pagePathFull"
      :task="$store.state.sale_personal.user.clientCardTask"
      :task-repeat="() => $store.dispatch('sale_personal/clientCardFetch')"
      :title="pageTitle"
      v-bind="bindRouterWrapper"
      @hide="onHide"
  >
    <div class="q-px-md q-px-lg-none q-mt-md q-mt-lg-none relative-position">

      <template v-if="card.FETCHED">

        <div class=" q-pb-md">

          <div
              class="c-card q-px-md q-py-md"
              style="max-width: 430px;"
              v-bind="bind"
          >

            <div>Ваш текущий бонусный ранг</div>

            <div class="q-mt-sm s-font-4xl text-weight-boldest q-pb-md">
           вв   {{ card.LEVEL_NAME || 'Ранг 1' }}
            </div>
вв
            <div class="q-mt-sm text-right" v-if="bonusesExpireDays>0">
              Бонусы действительны до {{ $util.date.timestampToFormat(card.BONUSES_EXPIRE, 'date') }}
            </div>

            <div class="q-gutter-md">

              <div
                  class="row items-center"
                  v-for="line in lines"
                  :key="line.label"
              >
                <div class="col-24 col-md-auto text-weight-medium s-font-md flex items-center ">
                  {{ line.label }}
                </div>
                <div class="col-24 col-md-auto q-ml-md-auto text-weight-medium">
                  <span class="c-impval s-font-2xl text-bold">{{ line.value }}</span> {{ line.suffix }}
                </div>
              </div>

            </div>

          </div>
        </div>

        <template v-if="level && false">

          <div v-if="level.needMore">

            <div class="q-pb-lg q-mb-md q-mt-xl">
              Чтобы достигнуть следующего бонусного ранга потратьте необходимую сумму:
            </div>

            <div class="row items-center q-py-md" style="max-width: 600px;" v-if="markerLabels.length">
              <div class="col-2"></div>
              <div class="col-20 col-lg-20">
                <q-slider
                    v-model="paidSumm"
                    :label-value="'За месяц потрачено ' + paidSumm + '₽'"
                    :marker-labels="markerLabels"
                    :max="6000"
                    :min="0"
                    color="green"
                    label-always
                    readonly
                    track-color="gray-3"
                >
                  <template v-slot:marker-label="{classes,style,marker}">
                    <div
                        :class="marker.classes"
                        :style="marker.style"
                        class="text-center"
                    >
                      <div class="s-font-sm text-weight-bold">
                        {{ $util.format.price(marker.value) }}
                      </div>
                      <div class="s-font-sm" v-if="marker.label">
                        {{ levelsMap[marker.label].title }}
                      </div>
                    </div>
                  </template>
                </q-slider>
              </div>
              <div class="col-2"></div>
            </div>
          </div>
          <div v-else>
            У вас максимальный бонусный ранг. Спасибо что выбираете нас!
          </div>
        </template>

        <div class="q-mt-lg q-mb-xl">
          <q-btn
              color="primary"
              label="подробнее о бонусной программе"
              outline
              to="/bonus/"
              unelevated
          />
        </div>

      </template>
      <template v-else>
        не удалось получить данные о бонусах
      </template>

    </div>
  </component>

</template>

<script>
import MRoute from "~module/user/component/profile/route.mixin"
import MVRoute from '~module/main/router/mixin/vroute'

export default {
  mixins: [MRoute, MVRoute],
  components: {},
  props: {},
  data() {
    return {
      page: {
        title: 'Бонусная система',
      },
    }
  },
  computed: {

    lines() {
      const res = []

      if (this.card) {

        res.push({
          label: 'Всего',
          value: this.card.BONUSES,
          suffix: 'бонусов'
        })

        if (this.card.BONUSES_PERCENT) {
          res.push({
            label: 'Можно оплатить бонусами',
            value: 'до ' + this.card.BONUSES_PERCENT + '%',
          })
        }

        if (this.card.ACCUMULATION_PERCENT) {
          res.push({
            label: 'Накопления от суммы заказов',
            value: this.card.ACCUMULATION_PERCENT + '%',
          })
        }

        if (this.card.DIS_SELF_PICKUP) {
          res.push({
            label: 'Скидка на самовывоз',
            value: this.card.DIS_SELF_PICKUP + '%',
          })
        }

        if (this.card.DIS_FIRST_ORDER) {
          res.push({
            label: 'Скидка на первый заказ',
            value: this.card.DIS_FIRST_ORDER + '%',
          })
        }
      }

      return res
    },

    actions() {
      const result = [];
      return result
    },
    card() {
      return this.$store.state.sale_personal.user.clientCard
    },
    bind() {
      const res = {
        class: {}
      }
      res.class['level-' + this.card.LEVEL_CODE] = true
      return res
    },

    markerLabels() {
      if (this.card.LEVEL_CODE === 'base') {
        return [
          {value: 0, label: 'base'},
          {value: 3000, label: 'silver'},
          {value: 6000, label: 'gold'},
        ]
      } else if (this.card.LEVEL_CODE === 'silver') {
        return [
          {value: 0, label: ''},
          {value: 6000, label: 'gold'},
        ]
      }
      return []
    },
    level() {
      return this.levelsMap[this.card.LEVEL_CODE]
    },
    levelsMap() {
      return this.levels.reduce((map, item) => {
        map[item.code] = item
        return map
      }, {})
    },
    levels() {
      return [
        {
          code: 'base',
          title: 'Базовый',
          price: 0,
          needMore: true,
          index: 1
        },
        {
          code: 'silver',
          title: 'Серебрянный',
          price: 3000,
          needMore: true,
          index: 2
        },
        {
          code: 'gold',
          title: 'Золотой',
          price: 6000,
          needMore: false,
          index: 3
        },
        {
          code: 'special',
          title: 'Особый',
          needMore: false,
          hidden: true
        },
      ]
    },
    paidSumm() {
      return this.card.MONTH_SPENT
    },
    progress() {
      return (this.paidSumm / 6000)
    },
    bonusesExpireDays() {
      return this.card && this.card.BONUSES_EXPIRE ? this.$util.date.duration(Date.now(), parseInt(this.card.BONUSES_EXPIRE)) : 0
    },
    bonusMax() {
      if (this.card && this.card.BONUSES_PERCENT) {
        return Math.round((this.card.BONUSES / 100) * this.card.BONUSES_PERCENT)
      }
      return 0
    },
  },
  methods: {},
  async mounted() {
    await this.$store.dispatch('sale_personal/clientCardFetch')
  }
}
</script>
<style lang="scss" scoped>

.c-card {
  border-radius: 10px;
}

.c-card {
  background: rgb(189, 163, 24);
  background: linear-gradient(79deg, rgba(189, 163, 24, 1) 0%, rgba(218, 196, 75, 1) 100%);
  color: #fff;
}

.c-row {
  border-bottom: 3px dashed #eee;
}

.c-impval {

}

</style>
