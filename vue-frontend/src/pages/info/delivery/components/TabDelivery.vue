<template>
  <div v-if="isMounted">

    <div class="c-row row q-col-gutter-md --with-map">

      <div class="col-24 col-lg-16 order-lg-last">

        map
      </div>

      <div class="col-24 col-lg-8">

        <ui-radios
            v-model="activeZoneId"
            :hideRadio="true"
            :items="zones"
            :separator="true"
            class="c-pickup-department__radios "
            :class="{
              'full-height': $q.screen.gt.md
            }"
            option-label="NAME"
            option-value="ID"
            :scroll-height="$q.screen.gt.md ? '80vh' : '40vh'"
            @input="onZoneInput"
        >
          <template v-slot:option="option">
            <div class="s-font-md">{{ option.PREVIEW_TEXT }}</div>
            <div class="s-font-xs text-weight-bold text-black q-pr-md">{{ option.PROP_FREE_TEXT }}</div>
          </template>
        </ui-radios>

      </div>

    </div>


  </div>
</template>

<script>
export default {
  data() {
    return {
      mapLoading: true,
      isMounted: false,
      activeZoneId: null,
    }
  },
  mounted() {
    this.fetch();
    this.isMounted = true
  },
  methods: {
    async fetch() {
      await this.$store.dispatch('sale/ensureDeliveryZones')
    },
    onMapLoaded(map) {
      this.map = map;
      if (this.pickupDepartment) {
        this.onDepartmentInput()
      }
    },
    onZoneInput(zoneId) {

      this.$nextTick(async () => {

        const polygon = this.zonesPolygons.find(item => item.ID === zoneId)

        if (polygon) {
          const coords = polygon.coords[0][0]
          this.map.balloon.close();
          this.map.balloon.open(coords, this.getZoneBalloon(), {autoPan: false});
        }

        if (this.$q.screen.lt.md) {
          this.$util.dom.scrollTo({el: '.c-map', target: 'body', offset: 0, duration: true})
        }

      })
    },
    getZoneBalloon() {
      if (!this.activeZone)
        return null
      return `
        <div class="c-baloon">
          <div class="s-font-sm text-weight-bold">${this.activeZone.PROP_FREE_TEXT}</div>
        </div>
      `
    },

  },
  watch: {

  },
  computed: {

    activeZone() {
      return this.zones.find(entity => entity.ID === this.activeZoneId)
    },
    activeZoneCoords() {
      return this.activeZone ? [this.activeZone.COORDS.LON, this.activeZone.COORDS.LAT] : null
    },
    zones() {
      return this.$store.getters['sale/deliveryZones']
    },
    zonesPolygons() {

      return this.zones.reduce((map, item) => {

        const poligons = Array.isArray(item.PROP_GEOJSON) ? item.PROP_GEOJSON : [item.PROP_GEOJSON]

        let poligonIndex = 0

        for (const poligon of poligons) {

          poligonIndex++

          const data = {
            PID: item.ID + '-' + poligonIndex,
            ID: item.ID,
            NAME: item.NAME,
            coords: [poligon.geometry.coordinates[0].map((coord) => ([coord[0], coord[1]]))],
            fill: poligon.properties.fill,
            fillOpacity: 0.7,
            strokeColor: poligon.properties.fill,
            strokeWidth: 1,
            strokeOpacity: 1,
            description: item.PROP_FREE_FROM_PRICE,
          }

          const activeZoneId = this.activeZoneId

          if (activeZoneId) {
            if (activeZoneId === item.ID) {
              data.strokeWidth = 4
            } else {
              data.fillOpacity = 0.1
            }
          }

          map.push(data)
        }
        return map
      }, [])
    }
  }
}
</script>
<style lang="scss" scoped>

.c-map {
  height: 500px;

  .c-map__embed {
    height: 100%;
  }
}

.c-zone-name {

  @media (max-width: $breakpoint-sm-max) {
    font-weight: bold;
  }
}


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
      z-index: 3;
    }

  }
}

</style>
