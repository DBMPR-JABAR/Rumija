/*
 Navicat Premium Data Transfer

 Source Server         : Localhost
 Source Server Type    : MySQL
 Source Server Version : 50733
 Source Host           : localhost:3306
 Source Schema         : teman_jabar

 Target Server Type    : MySQL
 Target Server Version : 50733
 File Encoding         : 65001

 Date: 01/04/2022 16:23:54
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for rumija_report
-- ----------------------------
DROP TABLE IF EXISTS `rumija_report`;
CREATE TABLE `rumija_report`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `image` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `lat` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'Jangan Dibatasi 10 Digit',
  `long` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'Jangan Dibatasi 10 Digit',
  `keterangan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'Lubang Single',
  `status` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `ruas_jalan_id` char(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sup_id` int(11) NULL DEFAULT NULL,
  `uptd_id` int(11) NULL DEFAULT NULL,
  `kota_id` int(11) NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of rumija_report
-- ----------------------------
INSERT INTO `rumija_report` VALUES (5, NULL, NULL, NULL, 'Lubang Single', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 09:36:38', '2022-03-31 14:22:29');
INSERT INTO `rumija_report` VALUES (6, NULL, NULL, NULL, 'Lubang Single', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 10:02:55', '2022-03-31 14:21:39');
INSERT INTO `rumija_report` VALUES (7, NULL, NULL, NULL, 'Lubang Single', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 14:17:34', '2022-03-31 14:17:34');
INSERT INTO `rumija_report` VALUES (8, NULL, NULL, NULL, 'Lubang Single', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-31 14:23:59', '2022-03-31 14:23:59');

SET FOREIGN_KEY_CHECKS = 1;
