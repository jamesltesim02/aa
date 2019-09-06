package com.cyjz.pojo;

import javax.persistence.*;
import java.io.Serializable;
import java.util.Date;

@Table(name = "ty_position_agreement_relative")
public class TyPositionAgreementRelative implements Serializable{
    /**
     *
     */
    private static final long serialVersionUID = 1L;

    /**
     * 岗位协议关系表id
     */
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    /**
     * 岗位id
     */
    @Column(name = "position_id")
    private Long positionId;

    /**
     * 协议id
     */
    @Column(name = "agreement_id")
    private Long agreementId;

    /**
     * 协议状态
     */
    @Column(name = "status")
    private Integer status;

    /**
     * 创建时间
     */
    @Column(name = "create_time")
    private Date createTime;

    private String positionName;
    private String areementName;

    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public Long getPositionId() {
        return positionId;
    }

    public void setPositionId(Long positionId) {
        this.positionId = positionId;
    }

    public Long getAgreementId() {
        return agreementId;
    }

    public void setAgreementId(Long agreementId) {
        this.agreementId = agreementId;
    }

    public Integer getStatus() {
        return status;
    }

    public void setStatus(Integer status) {
        this.status = status;
    }

    public Date getCreateTime() {
        return createTime;
    }

    public void setCreateTime(Date createTime) {
        this.createTime = createTime;
    }

    public String getPositionName() {
        return positionName;
    }

    public void setPositionName(String positionName) {
        this.positionName = positionName;
    }

    public String getAreementName() {
        return areementName;
    }

    public void setAreementName(String areementName) {
        this.areementName = areementName;
    }
}