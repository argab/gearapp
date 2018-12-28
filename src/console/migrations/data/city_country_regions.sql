/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Дамп данных таблицы testapp.cities: ~6 251 rows (приблизительно)
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
INSERT IGNORE INTO `cities` (`city_id`, `country_id`, `important`, `region_id`, `title_ru`, `area_ru`, `region_ru`, `title_en`, `area_en`, `region_en`) VALUES
	(183, 4, 1, 1700503, 'Алматы', NULL, NULL, 'Алматы', NULL, NULL);
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;

-- Дамп данных таблицы testapp.countries: ~1 rows (приблизительно)
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT IGNORE INTO `countries` (`country_id`, `title_ru`, `title_en`) VALUES
	(4, 'Казахстан', 'Kazakhstan');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;

-- Дамп данных таблицы testapp.regions: ~14 rows (приблизительно)
/*!40000 ALTER TABLE `regions` DISABLE KEYS */;
INSERT IGNORE INTO `regions` (`region_id`, `country_id`, `title_ru`, `title_en`) VALUES
	(1700002, 4, 'Акмолинская область', 'Акмолинская область'),
	(1700309, 4, 'Актюбинская область', 'Актюбинская область'),
	(1700503, 4, 'Алма-Атинская область', 'Алма-Атинская область'),
	(1700945, 4, 'Атырауская область', 'Атырауская область'),
	(1701029, 4, 'Восточно-Казахстанская область', 'Восточно-Казахстанская область'),
	(1701315, 4, 'Жамбылская  область', 'Жамбылская  область'),
	(1701514, 4, 'Западно-Казахстанская область', 'Западно-Казахстанская область'),
	(1701716, 4, 'Карагандинская область', 'Карагандинская область'),
	(1701955, 4, 'Кустанайская область', 'Кустанайская область'),
	(1702235, 4, 'Кзыл-Ординская область', 'Кзыл-Ординская область'),
	(1702346, 4, 'Мангистауская область', 'Мангистауская область'),
	(1702392, 4, 'Павлодарская область', 'Павлодарская область'),
	(1702577, 4, 'Северо-Казахстанская область', 'Северо-Казахстанская область'),
	(1702873, 4, 'Южно-Казахстанская область', 'Южно-Казахстанская область');
/*!40000 ALTER TABLE `regions` ENABLE KEYS */;



/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
