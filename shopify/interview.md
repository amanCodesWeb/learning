## What is Shopify, and what is Liquid? How does Liquid work in a Shopify theme?

Shopify is a hosted, subscription-based e-commerce platform that allows businesses to build and manage online stores without developing the entire backend from scratch. It provides features such as product management, payment integrations, order management, customizable themes, and an app ecosystem.

Liquid is Shopify's server-side templating language. It allows developers to render dynamic store data, such as product titles, prices, and collections, in HTML templates. Liquid uses objects to access data, tags to implement logic and control flow, and filters to modify output.


## What is Liquid object? and what is the difference between a Liquid object, a tag, and a filter? Give one example of each.

A Liquid object in Shopify is a core building block in the Liquid templating language that represents dynamic store data—such as products, collections, customer accounts, and cart contents, and outputs it onto your storefront.

Liquid objects are used to access and display dynamic Shopify data, such as {{ product.title }}. 
Liquid tags control the logic and flow of templates, for example, {% if product.available %}.
Liquid filters modify output values, such as {{ product.title | upcase }}, which converts the product title to uppercase.


## What is the difference between a section, a block, a snippet, and a layout in Shopify themes?

### section
A section is a major, customizable part of a webpage. Merchants can often add, remove, and rearrange sections in the Shopify Theme Editor.

Examples: Image banner, featured collection, slideshow.
File: sections/image-banner.liquid

### block
A block is a smaller component inside a section. It lets merchants customize individual pieces of that section.

Examples: Heading, text, button inside an image banner.
Blocks are commonly defined inside a section's schema.

### snippets
A snippet is a reusable piece of Liquid code that can be included in other theme files.

Examples: Product price, product card, icon.
File: snippets/price.liquid
Commonly rendered using {% render 'price' %}.

### layout
A layout is the overall HTML structure that wraps a page and provides shared elements.

Examples: Header, footer, main content area, global scripts.
File: layout/theme.liquid


Layout = The whole page structure.
Section = A major page area.
Block = A smaller component within a section.
Snippet = Reusable code that can be included elsewhere.

Shopify themes use layouts to define the overall page structure, sections to create customizable page areas, blocks to add smaller components within sections, and snippets to reuse Liquid code across different theme files. For example, a product page may use the main product section, which contains blocks for the title, price, and buy button, while a price snippet can be reused across the store.


## How do you create a custom section in Shopify, and what is the purpose of the schema inside a section file?

In Shopify, you generally create a file such as sections/custom-banner.liquid. Inside it, you write Liquid/HTML for the banner and define a {% schema %} block containing settings such as the heading, image, button text, and URL.

Here is a simplified example:

```
<section>
  <h2>{{ section.settings.heading }}</h2>

  {% if section.settings.banner_image != blank %}
    {{ section.settings.banner_image
      | image_url: width: 1600
      | image_tag }}
  {% endif %}
</section>

{% schema %}
{
  "name": "Custom banner",
  "settings": [
    {
      "type": "text",
      "id": "heading",
      "label": "Heading",
      "default": "Welcome to our store"
    },
    {
      "type": "image_picker",
      "id": "banner_image",
      "label": "Banner image"
    }
  ],
  "presets": [
    {
      "name": "Custom banner"
    }
  ]
}
{% endschema %}
```


## What is the difference between metafields and metaobjects in Shopify? Give an example of when you would use each.

Both metafields and metaobjects allow us to extend Shopify's standard data model. 
Metafields add custom data to existing resources, such as a product's care instructions or material. 
Metaobjects let us define structured data with multiple fields and create reusable entries, such as designer profiles, size guides, or testimonials. We can access both through Liquid when rendering storefront content.

Remember this distinction: metafields extend an existing resource; metaobjects define reusable structured records.

Metafield = Additional data for one resource
{{ product.metafields.custom.material }}

Metaobject = A structured record with multiple fields
{% for testimonial in shop.metaobjects.testimonial.values %}
  <h3>{{ testimonial.customer_name.value }}</h3>
  <p>{{ testimonial.review.value }}</p>
{% endfor %}


## Suppose a client asks you to customize a Shopify product page. How would you display product variants, update the price when a variant changes, and make the Add to Cart button work?

I would use Liquid to render the product information and available variants. Then I would use JavaScript to listen for variant changes and update the price, image, availability, and selected variant ID. For Add to Cart, I would submit the selected variant ID and quantity through Shopify's product form or Ajax Cart API, then update the cart UI and handle errors.


## What is the difference between the Shopify Ajax Cart API and the Storefront API? When would you use each?

The Ajax Cart API is used in Shopify themes to manage cart operations without reloading the page, such as adding a product, updating quantities, or removing items. 
The Storefront API is a GraphQL API used to retrieve storefront data and build custom shopping experiences. 
I would use the Ajax Cart API for a theme-based store's Add to Cart functionality and the Storefront API for a headless storefront built with a framework such as Hydrogen.


## A Shopify store is loading slowly. How would you identify the performance issues and improve the store's page speed?

First, I would measure the store using Google PageSpeed Insights and Lighthouse to identify bottlenecks. I would optimize image sizes, reduce unnecessary JavaScript and CSS, remove unused apps, and check whether third-party app scripts are slowing down the storefront. I would also review Liquid rendering and theme code. Finally, I would test the changes on mobile and desktop and compare the results, including Core Web Vitals, to confirm the improvements.


## What is a Shopify app, and how would you integrate a third-party API into a Shopify store?

A Shopify app extends a store's functionality or integrates external services. If a client needs a third-party API integration, I would review the API documentation, build or configure a suitable Shopify app, and make authenticated requests from a secure backend. I would keep API credentials server-side, handle errors and rate limits, and pass the required data to the storefront or relevant Shopify workflow. For an existing integration, I would first check whether a suitable app is already available.

Remember that installing an existing app and developing a custom API integration are two different approaches. Choose based on the client's requirements.


## A custom Shopify section is not displaying on the storefront, even though you have created the Liquid file. How would you troubleshoot the issue?

First, I would verify that the section file is in the correct directory and that its Liquid syntax and schema JSON are valid. Then I would check whether the section has been added to the correct template through the Theme Editor and whether any Liquid conditions prevent it from rendering. If the section contains JavaScript, I would inspect the browser console for errors. After fixing the issue, I would test the section in the theme preview and verify that it works on the relevant devices.