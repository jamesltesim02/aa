DROP TABLE `ty_agreement_sign`;
DROP TABLE `ty_offline_agreement`;

CREATE TABLE `ty_agreement_sign` (
`id` bigint(50) NOT NULL COMMENT '主键',
`employee_id` bigint(50) NULL COMMENT '员工id',
`agreement_id` bigint(50) NULL COMMENT '协议id',
`sign_time` datetime NULL COMMENT '签署时间',
`agreement_path` varchar(500) NULL COMMENT '协议文件路径',
`delflag` int(1) NULL COMMENT '是否已删除 0: 未删除, 1: 已删除',
`update_by` varchar(50) NULL COMMENT '修改人',
`update_time` datetime NULL COMMENT '修改时间',
PRIMARY KEY (`id`) 
);
CREATE TABLE `ty_offline_agreement` (
`id` bigint(50) NOT NULL COMMENT '主键',
`employee_id` bigint(50) NULL COMMENT '员工id',
`sign_time` datetime NULL COMMENT '签署时间',
`agreement_path` varchar(500) NULL COMMENT '协议路径',
`delflag` int(1) NULL COMMENT '删除状态 0: 未删除, 1: 已删除',
`expire_type` int(1) NULL COMMENT '有效期限类型: 1: 在职期内, 2: 签署起3年, 3: 签署起5年, 4: 离职2年内',
`status` int(1) NULL COMMENT '协议状态',
`create_by` varchar(50) NULL COMMENT '创建人',
`create_time` datetime NULL COMMENT '创建时间',
PRIMARY KEY (`id`) 
);
