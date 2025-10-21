--
-- PostgreSQL database dump
--

\restrict fr7Ni7iVfNhvAUIcjOvIdoVdBl2Yfu15mtRS46FAy8qJVccE5SLoUt4xzr1oIjV

-- Dumped from database version 16.10
-- Dumped by pg_dump version 16.10

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: cache; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.categories (
    id bigint NOT NULL,
    name character varying(100) NOT NULL,
    description text,
    is_active boolean DEFAULT true NOT NULL,
    sort_order integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: categories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;


--
-- Name: category_color; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.category_color (
    id bigint NOT NULL,
    category_id bigint NOT NULL,
    color_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: category_color_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.category_color_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: category_color_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.category_color_id_seq OWNED BY public.category_color.id;


--
-- Name: category_material; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.category_material (
    id bigint NOT NULL,
    category_id bigint NOT NULL,
    material_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: category_material_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.category_material_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: category_material_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.category_material_id_seq OWNED BY public.category_material.id;


--
-- Name: colors; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.colors (
    id bigint NOT NULL,
    color_name character varying(100) NOT NULL,
    percentage_increment numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    texture_path character varying(255),
    is_active boolean DEFAULT true NOT NULL,
    sort_order integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN colors.percentage_increment; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.colors.percentage_increment IS 'Price increment percentage';


--
-- Name: COLUMN colors.texture_path; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.colors.texture_path IS 'Path to 3D texture files';


--
-- Name: colors_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.colors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: colors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.colors_id_seq OWNED BY public.colors.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: global_cost_settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.global_cost_settings (
    id bigint NOT NULL,
    indirect_cost_percentage numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: global_cost_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.global_cost_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: global_cost_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.global_cost_settings_id_seq OWNED BY public.global_cost_settings.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: material_color; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.material_color (
    id bigint NOT NULL,
    material_id bigint NOT NULL,
    color_id bigint NOT NULL,
    category_id bigint,
    increase_value numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: material_color_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.material_color_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: material_color_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.material_color_id_seq OWNED BY public.material_color.id;


--
-- Name: materials; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.materials (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    category_id bigint NOT NULL,
    description text,
    unit_measure character varying(255) NOT NULL,
    unit_price numeric(10,2) NOT NULL,
    piece_size numeric(10,3) NOT NULL,
    piece_price numeric(10,2) NOT NULL,
    is_by_piece boolean DEFAULT true NOT NULL,
    supports_colors boolean DEFAULT false NOT NULL,
    has_dimensions boolean DEFAULT false NOT NULL,
    width numeric(8,3),
    height numeric(8,3),
    calculated_area numeric(10,6),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN materials.piece_size; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.materials.piece_size IS 'Tamaño de la pieza completa (ej: 6.4 metros)';


--
-- Name: COLUMN materials.piece_price; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.materials.piece_price IS 'Precio de la pieza completa';


--
-- Name: COLUMN materials.is_by_piece; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.materials.is_by_piece IS 'Si se maneja por piezas completas o por unidad';


--
-- Name: COLUMN materials.supports_colors; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.materials.supports_colors IS 'Si el material cambia a diferentes colores';


--
-- Name: COLUMN materials.has_dimensions; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.materials.has_dimensions IS 'Si el material se maneja por dimensiones (ancho x alto)';


--
-- Name: COLUMN materials.width; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.materials.width IS 'Ancho en metros';


--
-- Name: COLUMN materials.height; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.materials.height IS 'Alto en metros';


--
-- Name: COLUMN materials.calculated_area; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.materials.calculated_area IS 'Área calculada automáticamente';


--
-- Name: materials_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.materials_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: materials_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.materials_id_seq OWNED BY public.materials.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: product_color; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.product_color (
    id bigint NOT NULL,
    product_id bigint NOT NULL,
    color_id bigint NOT NULL,
    quantity integer DEFAULT 1 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: product_color_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.product_color_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: product_color_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.product_color_id_seq OWNED BY public.product_color.id;


--
-- Name: product_configurations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.product_configurations (
    id bigint NOT NULL,
    user_id bigint,
    product_id bigint NOT NULL,
    name character varying(255),
    configuration json NOT NULL,
    price numeric(10,2) NOT NULL,
    material_breakdown json,
    session_id character varying(255),
    is_saved boolean DEFAULT false NOT NULL,
    is_quoted boolean DEFAULT false NOT NULL,
    is_ordered boolean DEFAULT false NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN product_configurations.name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.product_configurations.name IS 'Nombre personalizado para la configuración';


--
-- Name: COLUMN product_configurations.configuration; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.product_configurations.configuration IS 'Parámetros de configuración del producto';


--
-- Name: COLUMN product_configurations.price; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.product_configurations.price IS 'Precio calculado para esta configuración';


--
-- Name: COLUMN product_configurations.material_breakdown; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.product_configurations.material_breakdown IS 'Desglose detallado de materiales';


--
-- Name: COLUMN product_configurations.session_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.product_configurations.session_id IS 'ID de sesión para usuarios no autenticados';


--
-- Name: COLUMN product_configurations.is_saved; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.product_configurations.is_saved IS 'Si la configuración fue guardada por el usuario';


--
-- Name: COLUMN product_configurations.is_quoted; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.product_configurations.is_quoted IS 'Si se solicitó cotización';


--
-- Name: COLUMN product_configurations.is_ordered; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.product_configurations.is_ordered IS 'Si se realizó pedido';


--
-- Name: COLUMN product_configurations.notes; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.product_configurations.notes IS 'Notas adicionales del cliente';


--
-- Name: product_configurations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.product_configurations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: product_configurations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.product_configurations_id_seq OWNED BY public.product_configurations.id;


--
-- Name: product_cost_settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.product_cost_settings (
    id bigint NOT NULL,
    product_id bigint NOT NULL,
    direct_cost_percentage numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: product_cost_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.product_cost_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: product_cost_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.product_cost_settings_id_seq OWNED BY public.product_cost_settings.id;


--
-- Name: product_customizations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.product_customizations (
    id bigint NOT NULL,
    product_id bigint NOT NULL,
    user_id bigint NOT NULL,
    custom_name character varying(255) NOT NULL,
    custom_description text NOT NULL,
    custom_price numeric(10,2),
    modifications json,
    custom_image character varying(255),
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    admin_notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT product_customizations_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'submitted'::character varying, 'approved'::character varying, 'rejected'::character varying])::text[])))
);


--
-- Name: product_customizations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.product_customizations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: product_customizations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.product_customizations_id_seq OWNED BY public.product_customizations.id;


--
-- Name: product_material; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.product_material (
    id bigint NOT NULL,
    product_id bigint NOT NULL,
    material_id bigint NOT NULL,
    quantity numeric(10,3) DEFAULT '1'::numeric NOT NULL,
    used_quantity numeric(10,3) NOT NULL,
    waste_percentage numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    calculation_formula text,
    calculated_cost numeric(10,2),
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN product_material.quantity; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.product_material.quantity IS 'Cantidad de material necesaria';


--
-- Name: COLUMN product_material.used_quantity; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.product_material.used_quantity IS 'Cantidad real usada del material';


--
-- Name: COLUMN product_material.waste_percentage; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.product_material.waste_percentage IS 'Porcentaje de desperdicio';


--
-- Name: COLUMN product_material.calculation_formula; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.product_material.calculation_formula IS 'Fórmula para calcular la cantidad usada';


--
-- Name: COLUMN product_material.calculated_cost; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.product_material.calculated_cost IS 'Costo calculado para este producto';


--
-- Name: COLUMN product_material.notes; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.product_material.notes IS 'Notas adicionales sobre el cálculo';


--
-- Name: product_material_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.product_material_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: product_material_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.product_material_id_seq OWNED BY public.product_material.id;


--
-- Name: products; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.products (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    description text NOT NULL,
    price numeric(10,2),
    product_type character varying(255) DEFAULT 'gallery'::character varying NOT NULL,
    base_dimensions json,
    base_cost numeric(10,2),
    allows_customization boolean DEFAULT false NOT NULL,
    image character varying(255),
    user_id bigint NOT NULL,
    is_gallery_visible boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    model_3d_file character varying(255),
    model_3d_textures json,
    model_3d_materials json,
    model_3d_settings json,
    has_3d_model boolean DEFAULT false NOT NULL,
    model_scale numeric(8,4) DEFAULT '1'::numeric NOT NULL,
    category_id bigint,
    height numeric(8,2),
    width numeric(8,2),
    CONSTRAINT products_product_type_check CHECK (((product_type)::text = ANY ((ARRAY['gallery'::character varying, 'customizable'::character varying])::text[])))
);


--
-- Name: COLUMN products.model_3d_file; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.products.model_3d_file IS 'Ruta al archivo del modelo 3D (.glb, .gltf, .obj)';


--
-- Name: COLUMN products.model_3d_textures; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.products.model_3d_textures IS 'Array de texturas asociadas al modelo 3D';


--
-- Name: COLUMN products.model_3d_materials; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.products.model_3d_materials IS 'Materiales específicos del modelo 3D';


--
-- Name: COLUMN products.model_3d_settings; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.products.model_3d_settings IS 'Configuraciones del visor 3D (cámara, iluminación, etc.)';


--
-- Name: COLUMN products.has_3d_model; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.products.has_3d_model IS 'Indica si el producto tiene modelo 3D';


--
-- Name: COLUMN products.model_scale; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.products.model_scale IS 'Escala del modelo 3D';


--
-- Name: products_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.products_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: products_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.products_id_seq OWNED BY public.products.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    oauth_provider character varying(255),
    oauth_id character varying(255),
    password character varying(255) NOT NULL,
    phone character varying(255),
    address text,
    province character varying(255),
    city character varying(255),
    role character varying(255) DEFAULT 'client'::character varying NOT NULL,
    is_suspended boolean DEFAULT false NOT NULL,
    suspended_until timestamp(0) without time zone,
    suspension_reason character varying(255),
    last_login_at timestamp(0) without time zone,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT users_role_check CHECK (((role)::text = ANY ((ARRAY['admin'::character varying, 'client'::character varying])::text[])))
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: categories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);


--
-- Name: category_color id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.category_color ALTER COLUMN id SET DEFAULT nextval('public.category_color_id_seq'::regclass);


--
-- Name: category_material id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.category_material ALTER COLUMN id SET DEFAULT nextval('public.category_material_id_seq'::regclass);


--
-- Name: colors id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.colors ALTER COLUMN id SET DEFAULT nextval('public.colors_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: global_cost_settings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.global_cost_settings ALTER COLUMN id SET DEFAULT nextval('public.global_cost_settings_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: material_color id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.material_color ALTER COLUMN id SET DEFAULT nextval('public.material_color_id_seq'::regclass);


--
-- Name: materials id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.materials ALTER COLUMN id SET DEFAULT nextval('public.materials_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: product_color id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_color ALTER COLUMN id SET DEFAULT nextval('public.product_color_id_seq'::regclass);


--
-- Name: product_configurations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_configurations ALTER COLUMN id SET DEFAULT nextval('public.product_configurations_id_seq'::regclass);


--
-- Name: product_cost_settings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_cost_settings ALTER COLUMN id SET DEFAULT nextval('public.product_cost_settings_id_seq'::regclass);


--
-- Name: product_customizations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_customizations ALTER COLUMN id SET DEFAULT nextval('public.product_customizations_id_seq'::regclass);


--
-- Name: product_material id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_material ALTER COLUMN id SET DEFAULT nextval('public.product_material_id_seq'::regclass);


--
-- Name: products id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.products ALTER COLUMN id SET DEFAULT nextval('public.products_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: categories categories_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_name_unique UNIQUE (name);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: category_color category_color_category_id_color_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.category_color
    ADD CONSTRAINT category_color_category_id_color_id_unique UNIQUE (category_id, color_id);


--
-- Name: category_color category_color_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.category_color
    ADD CONSTRAINT category_color_pkey PRIMARY KEY (id);


--
-- Name: category_material category_material_category_id_material_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.category_material
    ADD CONSTRAINT category_material_category_id_material_id_unique UNIQUE (category_id, material_id);


--
-- Name: category_material category_material_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.category_material
    ADD CONSTRAINT category_material_pkey PRIMARY KEY (id);


--
-- Name: colors colors_color_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.colors
    ADD CONSTRAINT colors_color_name_unique UNIQUE (color_name);


--
-- Name: colors colors_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.colors
    ADD CONSTRAINT colors_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: global_cost_settings global_cost_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.global_cost_settings
    ADD CONSTRAINT global_cost_settings_pkey PRIMARY KEY (id);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: material_color material_color_material_id_color_id_category_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.material_color
    ADD CONSTRAINT material_color_material_id_color_id_category_id_unique UNIQUE (material_id, color_id, category_id);


--
-- Name: material_color material_color_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.material_color
    ADD CONSTRAINT material_color_pkey PRIMARY KEY (id);


--
-- Name: materials materials_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.materials
    ADD CONSTRAINT materials_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: product_color product_color_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_color
    ADD CONSTRAINT product_color_pkey PRIMARY KEY (id);


--
-- Name: product_color product_color_product_id_color_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_color
    ADD CONSTRAINT product_color_product_id_color_id_unique UNIQUE (product_id, color_id);


--
-- Name: product_configurations product_configurations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_configurations
    ADD CONSTRAINT product_configurations_pkey PRIMARY KEY (id);


--
-- Name: product_cost_settings product_cost_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_cost_settings
    ADD CONSTRAINT product_cost_settings_pkey PRIMARY KEY (id);


--
-- Name: product_customizations product_customizations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_customizations
    ADD CONSTRAINT product_customizations_pkey PRIMARY KEY (id);


--
-- Name: product_material product_material_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_material
    ADD CONSTRAINT product_material_pkey PRIMARY KEY (id);


--
-- Name: product_material product_material_product_id_material_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_material
    ADD CONSTRAINT product_material_product_id_material_id_unique UNIQUE (product_id, material_id);


--
-- Name: products products_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: colors_is_active_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX colors_is_active_index ON public.colors USING btree (is_active);


--
-- Name: colors_sort_order_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX colors_sort_order_index ON public.colors USING btree (sort_order);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: materials_has_dimensions_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX materials_has_dimensions_index ON public.materials USING btree (has_dimensions);


--
-- Name: materials_is_by_piece_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX materials_is_by_piece_index ON public.materials USING btree (is_by_piece);


--
-- Name: product_configurations_created_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX product_configurations_created_at_index ON public.product_configurations USING btree (created_at);


--
-- Name: product_configurations_session_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX product_configurations_session_id_index ON public.product_configurations USING btree (session_id);


--
-- Name: product_configurations_user_id_product_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX product_configurations_user_id_product_id_index ON public.product_configurations USING btree (user_id, product_id);


--
-- Name: product_material_material_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX product_material_material_id_index ON public.product_material USING btree (material_id);


--
-- Name: product_material_product_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX product_material_product_id_index ON public.product_material USING btree (product_id);


--
-- Name: products_allows_customization_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX products_allows_customization_index ON public.products USING btree (allows_customization);


--
-- Name: products_is_gallery_visible_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX products_is_gallery_visible_index ON public.products USING btree (is_gallery_visible);


--
-- Name: products_product_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX products_product_type_index ON public.products USING btree (product_type);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: users_is_suspended_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_is_suspended_index ON public.users USING btree (is_suspended);


--
-- Name: users_province_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_province_index ON public.users USING btree (province);


--
-- Name: users_role_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_role_index ON public.users USING btree (role);


--
-- Name: category_color category_color_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.category_color
    ADD CONSTRAINT category_color_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.categories(id) ON DELETE CASCADE;


--
-- Name: category_color category_color_color_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.category_color
    ADD CONSTRAINT category_color_color_id_foreign FOREIGN KEY (color_id) REFERENCES public.colors(id) ON DELETE CASCADE;


--
-- Name: category_material category_material_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.category_material
    ADD CONSTRAINT category_material_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.categories(id) ON DELETE CASCADE;


--
-- Name: category_material category_material_material_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.category_material
    ADD CONSTRAINT category_material_material_id_foreign FOREIGN KEY (material_id) REFERENCES public.materials(id) ON DELETE CASCADE;


--
-- Name: material_color material_color_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.material_color
    ADD CONSTRAINT material_color_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.categories(id) ON DELETE SET NULL;


--
-- Name: material_color material_color_color_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.material_color
    ADD CONSTRAINT material_color_color_id_foreign FOREIGN KEY (color_id) REFERENCES public.colors(id) ON DELETE CASCADE;


--
-- Name: material_color material_color_material_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.material_color
    ADD CONSTRAINT material_color_material_id_foreign FOREIGN KEY (material_id) REFERENCES public.materials(id) ON DELETE CASCADE;


--
-- Name: materials materials_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.materials
    ADD CONSTRAINT materials_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.categories(id) ON DELETE CASCADE;


--
-- Name: product_color product_color_color_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_color
    ADD CONSTRAINT product_color_color_id_foreign FOREIGN KEY (color_id) REFERENCES public.colors(id) ON DELETE CASCADE;


--
-- Name: product_color product_color_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_color
    ADD CONSTRAINT product_color_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE CASCADE;


--
-- Name: product_configurations product_configurations_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_configurations
    ADD CONSTRAINT product_configurations_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE CASCADE;


--
-- Name: product_configurations product_configurations_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_configurations
    ADD CONSTRAINT product_configurations_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: product_cost_settings product_cost_settings_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_cost_settings
    ADD CONSTRAINT product_cost_settings_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE CASCADE;


--
-- Name: product_customizations product_customizations_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_customizations
    ADD CONSTRAINT product_customizations_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE CASCADE;


--
-- Name: product_customizations product_customizations_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_customizations
    ADD CONSTRAINT product_customizations_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: product_material product_material_material_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_material
    ADD CONSTRAINT product_material_material_id_foreign FOREIGN KEY (material_id) REFERENCES public.materials(id) ON DELETE CASCADE;


--
-- Name: product_material product_material_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_material
    ADD CONSTRAINT product_material_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE CASCADE;


--
-- Name: products products_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.categories(id) ON DELETE SET NULL;


--
-- Name: products products_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict fr7Ni7iVfNhvAUIcjOvIdoVdBl2Yfu15mtRS46FAy8qJVccE5SLoUt4xzr1oIjV

--
-- PostgreSQL database dump
--

\restrict ZgxXZDGex9qiw7KvWoC2dZntd0Lg2M0bXZnsINPpfjF6aWy1QRHxqdzrYRCAhgG

-- Dumped from database version 16.10
-- Dumped by pg_dump version 16.10

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2025_10_02_000000_0_create_categories_table	1
5	2025_10_02_000000_1_create_materials_table	1
6	2025_10_09_223221_create_products_table	1
7	2025_10_09_223241_add_3d_model_support_to_products_table	1
8	2025_10_09_223303_add_category_id_to_products_table	1
9	2025_10_09_223325_create_colors_table	1
10	2025_10_09_223345_create_material_color_table	1
11	2025_10_09_223405_create_category_material_table	1
12	2025_10_09_223422_create_category_color_table	1
13	2025_10_09_223610_create_product_color_table	1
14	2025_10_09_223700_create_product_customizations_table	1
15	2025_10_09_223710_create_product_material_table	1
16	2025_10_09_223720_create_product_configurations_table	1
17	2025_10_17_000000_create_cost_settings_tables	1
18	2025_10_19_000001_add_height_and_width_to_products_table	2
\.


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 18, true);


--
-- PostgreSQL database dump complete
--

\unrestrict ZgxXZDGex9qiw7KvWoC2dZntd0Lg2M0bXZnsINPpfjF6aWy1QRHxqdzrYRCAhgG

