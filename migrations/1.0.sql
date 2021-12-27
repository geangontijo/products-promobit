ALTER TABLE `product_tag`
	DROP FOREIGN KEY `product_id`,
	DROP FOREIGN KEY `tag_id`;
ALTER TABLE `product_tag`
	ADD CONSTRAINT `product_id` FOREIGN KEY (`product_id`) REFERENCES `promobit_challenge`.`product` (`id`) ON UPDATE RESTRICT ON DELETE CASCADE,
	ADD CONSTRAINT `tag_id` FOREIGN KEY (`tag_id`) REFERENCES `promobit_challenge`.`tag` (`id`) ON UPDATE RESTRICT ON DELETE CASCADE;