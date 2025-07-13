### Instalacja i uruchomienie

```bash
git clone https://github.com/dodocanfly/fg-courier-manager.git
cd fg-courier-manager
composer install
```

W pliku `create_shipment.php` w tablicy `$inpostConfig` należy podmienić wartości `api_token` i `organization_id` a następnie wydać polecenie:

```bash
php create_shipment.php
```
