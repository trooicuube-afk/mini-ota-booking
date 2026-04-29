package vn.miniota.backend.model;

public class SeoConfig {

    private String title = "MiniOTA — Đặt phòng homestay nhanh, quản lý lịch trống rõ ràng";
    private String description = "MiniOTA giúp khách đặt phòng homestay theo ngày và số khách, chủ phòng quản lý lịch trống, giá và booking trên cùng một hệ thống.";
    private String canonical = "https://miniota.vn/";
    private String robots = "index,follow";
    private String twitterCard = "summary_large_image";
    private OgConfig og = new OgConfig();

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getCanonical() {
        return canonical;
    }

    public void setCanonical(String canonical) {
        this.canonical = canonical;
    }

    public String getRobots() {
        return robots;
    }

    public void setRobots(String robots) {
        this.robots = robots;
    }

    public String getTwitterCard() {
        return twitterCard;
    }

    public void setTwitterCard(String twitterCard) {
        this.twitterCard = twitterCard;
    }

    public OgConfig getOg() {
        return og;
    }

    public void setOg(OgConfig og) {
        this.og = og;
    }

    public static class OgConfig {

        private String title = "MiniOTA — Đặt phòng & quản lý homestay";
        private String description = "Tìm phòng homestay theo ngày, số khách, địa điểm. Chủ phòng quản lý lịch, giá, booking tập trung.";
        private String imageUrl = "https://cdn.miniota.vn/og/home-og-1200x630.jpg";
        private String imageAlt = "Phòng homestay view núi tại Đà Lạt";

        public String getTitle() {
            return title;
        }

        public void setTitle(String title) {
            this.title = title;
        }

        public String getDescription() {
            return description;
        }

        public void setDescription(String description) {
            this.description = description;
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
    }
}
