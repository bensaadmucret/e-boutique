# Cart
## Attributs :
* `items`: Liste de `CartItem`
* `total`: `Money`

## Méthodes :
* `addItem(CartItem)`: Ajoute un élément au panier.
* `removeItem(CartItem)`: Retire un élément du panier.
* `getItems()`: Retourne tous les éléments du panier.
* `getTotal()`: Calcule et retourne le total du panier.

---

# CartItem
## Attributs :
* `productId`: Identifiant du produit.
* `productType`: `ProductType`, indique le type de produit (ex. "PhysicalProduct", "DigitalProduct").
* `quantity`: `Quantity`, représente la quantité de ce produit.
* `price`: `Money`, représente le prix de l'article.

## Méthodes :
* `getProductId()`: Retourne l'identifiant du produit.
* `getProductType()`: Retourne le type du produit.
* `getQuantity()`: Retourne la quantité de l'article.
* `getPrice()`: Retourne le prix de l'article.
* `increaseQuantity(int)`: Augmente la quantité de l'article.
* `decreaseQuantity()`: Diminue la quantité de l'article.

---

# Money
## Attributs :
* `amount`: Montant de type `float`.
* `currency`: `Currency`, représente la devise.

## Méthodes :
* `__toString()`: Retourne une représentation en chaîne de caractères de la somme et de la devise.

---

# Currency (Enum)
## Valeurs possibles :
* `USD`
* `EUR`
* `GBP`
* `JPY`

## Méthodes :
* `symbol()`: Retourne le symbole associé à la devise.

---

# Quantity
## Attributs :
* `value`: Valeur de la quantité de type `int`.

## Méthodes :
* `getValue()`: Retourne la valeur de la quantité.

---

# ProductType
## Attributs :
* `type`: Chaîne de caractères représentant le type de produit.

## Méthodes :
* `getType()`: Retourne le type de produit sous forme de chaîne de caractères.
