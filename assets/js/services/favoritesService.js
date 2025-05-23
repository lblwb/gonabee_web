const FAVORITES_KEY = 'favorite_products';

export function getFavorites() {
    try {
        return JSON.parse(localStorage.getItem(FAVORITES_KEY)) || [];
    } catch {
        return [];
    }
}

export function saveFavorites(ids) {
    localStorage.setItem(FAVORITES_KEY, JSON.stringify(ids));
}

export function addToFavorites(productId) {
    const favorites = getFavorites();
    if (!favorites.includes(productId)) {
        favorites.push(productId);
        saveFavorites(favorites);
    }
}

export function removeFromFavorites(productId) {
    const updated = getFavorites().filter(id => id !== productId);
    saveFavorites(updated);
}

export function isFavorite(productId) {
    return getFavorites().includes(productId);
}
