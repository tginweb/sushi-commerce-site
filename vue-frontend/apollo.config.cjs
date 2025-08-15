/* eslint-env node */
// See https://www.apollographql.com/docs/devtools/apollo-config/
module.exports = {
  client: {
    service: {
      name: 'my-service',
      url: 'http://dev.sushi-new.prod.loc/api-mobile/graphql/',
    },
    // Files processed by the extension
    includes: ['src/**/*.vue', 'src/**/*.js', 'src/**/*.ts'],
  },
}
