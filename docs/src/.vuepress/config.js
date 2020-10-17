const { description } = require('../../package')

module.exports = {
  /**
   * Ref：https://v1.vuepress.vuejs.org/config/#title
   */
  title: 'Pesa PHP SDK',
  /**
   * Ref：https://v1.vuepress.vuejs.org/config/#description
   */
  description: description,

  /**
   * Extra tags to be injected to the page HTML `<head>`
   *
   * ref：https://v1.vuepress.vuejs.org/config/#head
   */
  head: [
    ['meta', { name: 'theme-color', content: '#3eaf7c' }],
    ['meta', { name: 'apple-mobile-web-app-capable', content: 'yes' }],
    ['meta', { name: 'apple-mobile-web-app-status-bar-style', content: 'black' }]
  ],

  /**
   * Theme configuration, here is the default theme configuration for VuePress.
   *
   * ref：https://v1.vuepress.vuejs.org/theme/default-theme-config.html
   */
  themeConfig: {
    smoothScroll: true,
     // Assumes GitHub. Can also be a full GitLab url.
     repo: 'openpesa/php-pesa',
     // Customising the header label
     // Defaults to "GitHub"/"GitLab"/"Bitbucket" depending on `themeConfig.repo`
     repoLabel: 'Contribute!',
 
     // Optional options for generating "Edit this page" link
 
     // if your docs are in a different repo from your main project:
     docsRepo: 'openpesa/php-pesa',
     // if your docs are not at the root of the repo:
     docsDir: 'docs/src',
     // if your docs are in a specific branch (defaults to 'master'):
     docsBranch: 'develop',
     // defaults to false, set to true to enable
     editLinks: true,
     // custom text for edit link. Defaults to "Edit this page"
     editLinkText: 'Help us improve this page!',
    
    lastUpdated: true,
    nav: [
      {
        text: 'Guide',
        link: '/docs/guide/',
      },      
    ],
    sidebar: {
      '/docs/': [{
          children: [
            'guide',
            'openpesa',
            
            'welcome',
            
            'requirements',   
            'psr_compliance',
            'credits',

            'installation',
            'composer',
            'c2b'
          ]}
      ],
    }
  },

  /**
   * Apply plugins，ref：https://v1.vuepress.vuejs.org/zh/plugin/
   */
  plugins: [
    '@vuepress/plugin-back-to-top',
    '@vuepress/plugin-medium-zoom',
  ]
}
