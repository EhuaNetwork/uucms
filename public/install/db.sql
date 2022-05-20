/*
 Navicat Premium Data Transfer

 Source Server         : uucms_ehua_pro
 Source Server Type    : MySQL
 Source Server Version : 50734
 Source Host           : 49.233.122.43:3306
 Source Schema         : uucms_ehua_pro

 Target Server Type    : MySQL
 Target Server Version : 50734
 File Encoding         : 65001

 Date: 20/05/2022 20:10:48
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for uu_cms_admin
-- ----------------------------
DROP TABLE IF EXISTS `uu_cms_admin`;
CREATE TABLE `uu_cms_admin`  (
  `id` int(11) NOT NULL COMMENT '管理员表',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `pass` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `uptime` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of uu_cms_admin
-- ----------------------------
INSERT INTO `uu_cms_admin` VALUES (1, 'admin', '$2y$10$eA4QySku.a74ej6lPBAXNuF1ThEZ1JhRNrrm236HVIpDI2KeL3hLS', '8.209.211.92', '2021-03-18 14:25:41');

-- ----------------------------
-- Table structure for uu_cms_article
-- ----------------------------
DROP TABLE IF EXISTS `uu_cms_article`;
CREATE TABLE `uu_cms_article`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章表',
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `body` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  `update_time` datetime(0) NULL DEFAULT NULL,
  `status` int(3) NULL DEFAULT 1,
  `view` int(255) NULL DEFAULT 0,
  `top` int(255) NULL DEFAULT 0,
  `d` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `k` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `t` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `imgs` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of uu_cms_article
-- ----------------------------

-- ----------------------------
-- Table structure for uu_cms_article_lib
-- ----------------------------
DROP TABLE IF EXISTS `uu_cms_article_lib`;
CREATE TABLE `uu_cms_article_lib`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章辅表',
  `aid` int(11) NULL DEFAULT NULL,
  `lib_as` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `lib_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `lib_value` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of uu_cms_article_lib
-- ----------------------------

-- ----------------------------
-- Table structure for uu_cms_banner
-- ----------------------------
DROP TABLE IF EXISTS `uu_cms_banner`;
CREATE TABLE `uu_cms_banner`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '首图',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `img2` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of uu_cms_banner
-- ----------------------------

-- ----------------------------
-- Table structure for uu_cms_echo
-- ----------------------------
DROP TABLE IF EXISTS `uu_cms_echo`;
CREATE TABLE `uu_cms_echo`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '外部用户表单',
  `area` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '面积',
  `budget` decimal(65, 2) NULL DEFAULT NULL COMMENT '预算',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `body` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `phone`(`phone`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of uu_cms_echo
-- ----------------------------

-- ----------------------------
-- Table structure for uu_cms_link
-- ----------------------------
DROP TABLE IF EXISTS `uu_cms_link`;
CREATE TABLE `uu_cms_link`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '友情链接',
  `url` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` int(1) NULL DEFAULT 1,
  `top` int(255) NULL DEFAULT 0,
  `create_time` datetime(0) NULL DEFAULT NULL,
  `update_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of uu_cms_link
-- ----------------------------

-- ----------------------------
-- Table structure for uu_cms_log_update
-- ----------------------------
DROP TABLE IF EXISTS `uu_cms_log_update`;
CREATE TABLE `uu_cms_log_update`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '更新日志',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '描述',
  `this_lv` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '适用版本',
  `to_lv` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '版本号',
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '状态',
  `issues` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '问题标记',
  `data` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '数据集',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `share` int(255) NULL DEFAULT 0,
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `zip_jie_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of uu_cms_log_update
-- ----------------------------
INSERT INTO `uu_cms_log_update` VALUES (2, '5.2.3更新日志', '修复了列表页404的BUG', '[5.2.2]', '5.2.3', '1', '无', '{\"\"}', '2022-03-11 09:28:40', 1, '\\package\\update\\ecms_5.2.3.zip', '/application');
INSERT INTO `uu_cms_log_update` VALUES (3, '5.2.4更新日志', '该版本需安装SG11.4', '[5.2.3]', '5.2.4', '1', '无', '{\"\"}', '2022-03-12 17:19:23', 1, '\\package\\update\\ecms_5.2.4.zip', '/application');
INSERT INTO `uu_cms_log_update` VALUES (4, '5.2.4补丁', '5.2.4补丁包-修复了一些问题', '[5.2.4]', '5.2.5', '1', '无', '{\"\"}', '2022-03-12 17:19:23', 1, '\\package\\update\\ecms_5.2.4-1.zip', '/application');
INSERT INTO `uu_cms_log_update` VALUES (5, '5.2.6更新日志', '优化核心逻辑', '[5.2.5]', '5.2.6', '1', '无', '{\"\"}', '2022-04-10 01:17:35', 1, '\\package\\update\\ecms_5.2.6.zip', '/application');
INSERT INTO `uu_cms_log_update` VALUES (6, '5.2.7更新日志', '修复了数据库在线更新', '[5.2.6]', '5.2.7', '1', '无', '{\"\"}', '2022-04-10 01:17:35', 1, '\\package\\update\\ecms_5.2.7.zip', '/application');

-- ----------------------------
-- Table structure for uu_cms_mod
-- ----------------------------
DROP TABLE IF EXISTS `uu_cms_mod`;
CREATE TABLE `uu_cms_mod`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '栏目表',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  `type` int(255) NULL DEFAULT NULL COMMENT '类型 单页  列表页',
  `upid` int(11) NULL DEFAULT NULL COMMENT '上级id',
  `m` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `c` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `a` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `upid_all` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '上级关系表',
  `top` int(255) NULL DEFAULT NULL,
  `is_nav` int(255) NULL DEFAULT 1 COMMENT '是否是导航',
  `nourl` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `body` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `t` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `d` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `k` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 156 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of uu_cms_mod
-- ----------------------------
INSERT INTO `uu_cms_mod` VALUES (1, '关于我们', 1, 0, 'index', 'about', 'info', '[0]', 80, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `uu_cms_mod` VALUES (3, '产品展示', 2, 0, 'index', 'pro', 'index', '[0]', 75, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `uu_cms_mod` VALUES (4, '新闻资讯', 2, 0, 'index', 'news', 'index', '[0]', 70, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `uu_cms_mod` VALUES (6, '联系我们', 2, 0, 'index', 'about', 'link', '[0]', 30, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `uu_cms_mod` VALUES (93, '公司新闻', 2, 4, 'index', 'news', 'index', '[0][4]', 0, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `uu_cms_mod` VALUES (94, '行业新闻', 2, 4, 'index', 'news', 'index', '[0][4]', 0, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `uu_cms_mod` VALUES (99, '网站首页', 1, 0, 'index', 'index', 'index', '[0]', 90, 1, '/', NULL, NULL, NULL, NULL);
INSERT INTO `uu_cms_mod` VALUES (152, '分类一', 2, 3, 'index', 'pro', 'index', '[0][3]', 0, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `uu_cms_mod` VALUES (153, '分类二', 2, 3, 'index', 'pro', 'index', '[0][3]', 0, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `uu_cms_mod` VALUES (154, '分类三', 2, 3, 'index', 'pro', 'index', '[0][3]', 0, 1, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for uu_cms_pulg
-- ----------------------------
DROP TABLE IF EXISTS `uu_cms_pulg`;
CREATE TABLE `uu_cms_pulg`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '插件',
  `pulg_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '插件名称',
  `author` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '作者',
  `describe` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '描述',
  `lavel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '版本',
  `route` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '授权方法',
  `share` int(255) NULL DEFAULT 0 COMMENT '共享',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `install` int(255) NULL DEFAULT 0 COMMENT '是否安装',
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '路径',
  `pulg` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '英文插件名',
  `into` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '入口方法',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of uu_cms_pulg
-- ----------------------------
INSERT INTO `uu_cms_pulg` VALUES (12, '产品批量导入', 'Ehua', '介绍', '2.0', 'init', 1, '2022-05-08 00:43:34', 1, '\\package\\pulg\\Import.zip', 'Import', NULL);
INSERT INTO `uu_cms_pulg` VALUES (13, '织梦系统转换', 'Ehua', '介绍', '2.0', 'init', 1, '2022-05-08 00:46:50', 1, '\\package\\pulg\\Dede.zip', 'Dede', NULL);
INSERT INTO `uu_cms_pulg` VALUES (14, 'E采集', 'Ehua', '介绍', '2.0', 'init', 1, '2022-05-08 01:44:03', 1, '\\package\\pulg\\ecaiji.zip', 'ecaiji', NULL);

-- ----------------------------
-- Table structure for uu_cms_system
-- ----------------------------
DROP TABLE IF EXISTS `uu_cms_system`;
CREATE TABLE `uu_cms_system`  (
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `as` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `top` int(255) NULL DEFAULT 0
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of uu_cms_system
-- ----------------------------
INSERT INTO `uu_cms_system` VALUES ('company', 'UU建站', '公司名称', NULL);
INSERT INTO `uu_cms_system` VALUES ('address', '中国 烟台', '联系地址', NULL);
INSERT INTO `uu_cms_system` VALUES ('mail', ' admin@admin.com', '联系邮箱', NULL);
INSERT INTO `uu_cms_system` VALUES ('info', '公司简介...', '公司简介', NULL);
INSERT INTO `uu_cms_system` VALUES ('web_keywords', 'UU建站', 'K', NULL);
INSERT INTO `uu_cms_system` VALUES ('web_description', 'UU建站', 'D', NULL);
INSERT INTO `uu_cms_system` VALUES ('web_title', 'UU建站', 'T', NULL);
INSERT INTO `uu_cms_system` VALUES ('link_phone', '188-8888-888', '联系电话', NULL);
INSERT INTO `uu_cms_system` VALUES ('icp', '鲁ICP备xxxxxxx号', '备案信息', NULL);
INSERT INTO `uu_cms_system` VALUES ('map', '121.004258,37.663399', '地图坐标', 0);
INSERT INTO `uu_cms_system` VALUES ('link_name', 'UU', '联系人', 0);
INSERT INTO `uu_cms_system` VALUES ('link_tel', '188-8888-888', '电话', 0);

-- ----------------------------
-- Table structure for uu_cms_system_admin
-- ----------------------------
DROP TABLE IF EXISTS `uu_cms_system_admin`;
CREATE TABLE `uu_cms_system_admin`  (
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `as` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `top` int(255) NULL DEFAULT 0,
  `option` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of uu_cms_system_admin
-- ----------------------------
INSERT INTO `uu_cms_system_admin` VALUES ('editor', 'UEditor', '编辑器设置', 0, '[{\"name\": \"UE\",\"value\": \"UEditor\"},{\"name\": \"wang\",\"value\": \"wangEditor\"}]');
INSERT INTO `uu_cms_system_admin` VALUES ('upimg', 'Local', '图片上传设置', 0, '[{\"name\": \"本地\",\"value\": \"Local\"}]');
INSERT INTO `uu_cms_system_admin` VALUES ('url', 'PHPCMS', 'URL兼容', 0, '[{\"name\": \"织梦\",\"value\": \"DEDE\"},{\"name\": \"PHPCMS\",\"value\": \"PHPCMS\"}]');

-- ----------------------------
-- Table structure for uu_cms_user
-- ----------------------------
DROP TABLE IF EXISTS `uu_cms_user`;
CREATE TABLE `uu_cms_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户表',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `pass` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  `money` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of uu_cms_user
-- ----------------------------
INSERT INTO `uu_cms_user` VALUES (1, 'admin', '$2y$10$eA4QySku.a74ej6lPBAXNuF1ThEZ1JhRNrrm236HVIpDI2KeL3hLS', '2022-04-10 16:47:51', '1', '36.113.99.127');

-- ----------------------------
-- Table structure for uu_cms_view
-- ----------------------------
DROP TABLE IF EXISTS `uu_cms_view`;
CREATE TABLE `uu_cms_view`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `source` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of uu_cms_view
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
