<template>
  <div v-if="isMounted" class="com-page">

    <div class="c-row row q-col-gutter-md --with-map">

      <div class="col-24 col-md-15 order-md-last">

        MAP

      </div>
      <div class="col-24 col-md-9 ">

        <ui-radios
            v-model="pickupDepartmentId"
            :hideRadio="true"
            :items="entitiesComp"
            class="c-pickup-department__radios full-height"
            optionDisable="NONE"
            optionLabel="NAME"
            optionValue="ID"
            scroll-height="80vh"
            @input="onItemNav"
            v-if="$q.screen.gt.sm"
        >
          <template v-slot:option="option">
            <div class="text-weight-bold">{{ option.NAME }}</div>
            <div class="s-font-sm q-mt-xs">{{ option.WORKTIME }}</div>
            <div v-if="option.DISABLE" class="s-font-sm q-mt-xs text-red">{{ option.DISABLE_REASON }}</div>
          </template>
        </ui-radios>

        <ui-radios
            v-model="pickupDepartmentId"
            :items="entitiesComp"
            class="c-pickup-department__radios"
            v-else
            optionDisable="NONE"
            optionLabel="NAME"
            optionValue="ID"
            :hideRadio="true"
            scroll-height="40vh"
            @input="onItemNav"
        >
          <template v-slot:option="option">
            <div class="text-weight-bold">{{ option.NAME }}</div>
            <div class="s-font-sm q-mt-xs">{{ option.WORKTIME }}</div>
            <div v-if="option.DISABLE " class="s-font-sm q-mt-xs text-red">{{ option.DISABLE_REASON }}</div>
          </template>
        </ui-radios>

      </div>

    </div>


  </div>
</template>

<script>
import generateQueryInfo from "~module/main/graphql/lib/generate-query-info";


export default {
  apollo: {
    entities: generateQueryInfo(
        'entities',
        require('~module/company/gql/query/office_list.gql'),
        {},
        {varPath: 'queryVars'}
    ),
  },
  data() {
    return {
      pickupDepartmentId: true,
      mapVisible: true,
      mapLoading: true,
      isMounted: false,
      controls: ['zoomControl'],
      coords: [104.32109, 52.28458],
      zoom: 12,
      queries: {
        entities: {
          state: {
            isLoading: false
          },
          result: null
        },
      },
      time: '02.11.2022 02:01'
    }
  },
  mounted() {
    this.isMounted = true
  },
  methods: {
    onMapReady(m) {
      this.map = m
    },
    onItemNav() {
      if (!this.map)
        return;

      this.$nextTick(async () => {
        const office = this.entityActive
        if (office) {
          await this.map.setZoom(14)
          this.map.panTo([office.COORDS.LON, office.COORDS.LAT], {
            flying: true
          });
          this.map.balloon.close();
          this.map.balloon.open([office.COORDS.LON, office.COORDS.LAT], this.getBalloonTemplate(), {autoPan: false});

          if (this.$q.screen.lt.md) {
            this.$util.dom.scrollTo({el: '.c-map', target: 'body', offset: 0, duration: true})
          }
        }
      })
    },
    getBalloonTemplate() {
      if (!this.entityActive)
        return null
      return `
        <div class="c-baloon">
          <div class="s-font-sm text-weight-bold">${this.entityActive.NAME}</div>
          <div class="q-mt-sm">${this.entityActive.WORKTIME}</div>
        </div>
      `
    },
  },
  computed: {
    timestamp() {
      return (this.$util.date.parseTime(this.time, 'DD.MM.YYYY HH:mm') || 0) / 1000
    },

    queryVars() {
      return {
        filter: {
          ROLES_XMLID: {eq: "pickup"}
        },
        nav: {
          limit: 20
        },
        time: this.timestamp
      }
    },
    entitiesComp() {
      return this.$store.getters['iblock/prepareElements'](this.queries.entities.result && this.queries.entities.result)
    },
    entityActive() {
      return this.entitiesComp.find(entity => entity.ID === this.pickupDepartmentId)
    },
  }
}
</script>
<style lang="scss" scoped>


.c-pickup-department__radios {

  border-radius: 10px;
  border: 1px solid #ddd;
  overflow: hidden;
  height: 100%;

  /deep/ {
    .q-list {

    }

    .c-option {
      padding-left: 20px;
    }

    .--selected {
      background-color: rgba(237, 237, 237, 0.5);

      &:before {
        content: "";
        position: absolute;
        width: 3px;
        height: 100%;
        left: -1px;
        top: 0;
        background: $primary;
        transition: all 0.3s;
      }
    }
  }
}

.c-map {
  border-radius: 10px;
  overflow: hidden;
  border: 1px solid #DDD;
}

.c-row {
  &.--with-map {

    .c-pickup-department__radios {
      background-color: #fff;
      z-index: 4;
    }

  }
}

</style>
