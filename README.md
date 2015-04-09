magento-multipledelivery-module
===============================

Multiple Delivery Methods module for Magento


Adds a delivery method in Magento configuration which allows the adding of multiple delivery methods using a json encoded string.


**Available Options**
  - enabled (boolean)
  - title (string)
  - description (string)
  - price (float)
  - cost (float)
  - postcodes (array)
  - postcode_condition (allow|restrict)


**An example setup**

```
{
    "nextday": {
        "enabled": true,
        "title": "Next Day",
        "description": "Next day delivery",
        "price": 5,
        "postcodes": ["PO20","PO19"],
        "postcode_condition": "restrict"
    },
    "nextdaybefore10": {
        "enabled": true,
        "title": "Next Day (before 10am)",
        "description": "Receive your items before 10am",
        "price": 10,
        "postcodes": ["PO20","PO19"],
        "postcode_condition": "allow"
    }
}
```
