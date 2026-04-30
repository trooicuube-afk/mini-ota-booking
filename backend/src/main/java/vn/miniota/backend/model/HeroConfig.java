package vn.miniota.backend.model;

import java.util.ArrayList;
import java.util.List;

public class HeroConfig {

    private String badge = "Mini OTA · Booking + Property Management";
    private String titleLine1 = "Đặt phòng homestay nhanh,";
    private String titleLine2 = "quản lý lịch trống rõ ràng";
    private String titleHighlight = "rõ ràng";
    private String subtitle = "Tìm phòng phù hợp theo ngày, số khách và địa điểm. Chủ phòng có thể quản lý lịch, giá và booking trên cùng một hệ thống.";
    private String imageUrl = "https://cdn.miniota.vn/landing/hero-da-lat.jpg";
    private String imageAlt = "Phòng homestay view núi tại Đà Lạt với ban công gỗ và nội thất ấm áp";
    private List<TrustBadge> trustBadges = new ArrayList<>();
    private String searchCta = "Tìm phòng";
    private String searchAction = "/app/rooms";

    public String getBadge() {
        return badge;
    }

    public void setBadge(String badge) {
        this.badge = badge;
    }

    public String getTitleLine1() {
        return titleLine1;
    }

    public void setTitleLine1(String titleLine1) {
        this.titleLine1 = titleLine1;
    }

    public String getTitleLine2() {
        return titleLine2;
    }

    public void setTitleLine2(String titleLine2) {
        this.titleLine2 = titleLine2;
    }

    public String getTitleHighlight() {
        return titleHighlight;
    }

    public void setTitleHighlight(String titleHighlight) {
        this.titleHighlight = titleHighlight;
    }

    public String getSubtitle() {
        return subtitle;
    }

    public void setSubtitle(String subtitle) {
        this.subtitle = subtitle;
    }

    public String getImageUrl() {
        return imageUrl;
    }

    public void setImageUrl(String imageUrl) {
        this.imageUrl = imageUrl;
    }

    public String getImageAlt() {
        return imageAlt;
    }

    public void setImageAlt(String imageAlt) {
        this.imageAlt = imageAlt;
    }

    public List<TrustBadge> getTrustBadges() {
        return trustBadges;
    }

    public void setTrustBadges(List<TrustBadge> trustBadges) {
        this.trustBadges = trustBadges;
    }

    public String getSearchCta() {
        return searchCta;
    }

    public void setSearchCta(String searchCta) {
        this.searchCta = searchCta;
    }

    public String getSearchAction() {
        return searchAction;
    }

    public void setSearchAction(String searchAction) {
        this.searchAction = searchAction;
    }

    public static class TrustBadge {

        private String label;
        private String colorToken;

        public TrustBadge() {
        }

        public TrustBadge(String label, String colorToken) {
            this.label = label;
            this.colorToken = colorToken;
        }

        public String getLabel() {
            return label;
        }

        public void setLabel(String label) {
            this.label = label;
        }

        public String getColorToken() {
            return colorToken;
        }

        public void setColorToken(String colorToken) {
            this.colorToken = colorToken;
        }
    }
}
