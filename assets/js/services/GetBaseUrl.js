export default function getBaseUrl() {
  return document.querySelector('meta[name="base_url"]').content;
}
